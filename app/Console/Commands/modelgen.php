<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Laravel\Prompts\Prompt;

use function Laravel\Prompts\multiselect;
use function Laravel\Prompts\select;
use function Laravel\Prompts\confirm;

enum ModelType: int
{
    case BASE = 0;
    case MODEL = 1;
}

class ModelGen extends Command
{
    protected $signature = 'split:modelgen {--types-only : Generate only TypeScript interfaces}';
    protected $description = 'Generate TypeScript models and interfaces from Laravel models';

    protected $outputDirs;
    protected $typesOutputPath;
    protected $models;
    protected Collection $selectedModels;

    public function __construct()
    {
        parent::__construct();
        $this->outputDirs =  [
            ModelType::BASE->value => base_path('resources/js/Lib/models/_base/'),
            ModelType::MODEL->value => base_path('resources/js/Lib/models/'),
        ];
        $this->typesOutputPath = config('typegen.output', base_path('resources/js/types/models.d.ts'));
    }

    public function handle()
    {
        if (!$this->validateEnvironment()) {
            return;
        }

        $this->loadModels();

        if ($this->shouldUseAllModels()) {
            $this->selectedModels = $this->models;
        } else {
            $this->selectedModels = $this->getSelectedModels();
        }

        if ($this->selectedModels->isEmpty()) {
            $this->error('No models selected.');
            return;
        }

        if ($this->option('types-only')) {
            $this->generateTypeDefinitions();
        } else {
            $this->generateTypeDefinitions();
            $this->generateModelClasses();
        }

        $this->info('Generation completed successfully.');
    }

    protected function validateEnvironment(): bool
    {
        if (config('database.default') !== 'pgsql') {
            $this->error('Only PostgreSQL is supported at the moment.');
            return false;
        }

        $this->info('Using PostgreSQL database driver.');

        if (!$this->option('types-only')) {
            foreach ($this->outputDirs as $outputDir) {
                if (!is_dir($outputDir)) {
                    mkdir($outputDir, 0755, true);
                }
            }
        }

        $typesDir = dirname($this->typesOutputPath);
        if (!is_dir($typesDir)) {
            mkdir($typesDir, 0755, true);
        }

        return true;
    }

    protected function shouldUseAllModels(): bool
    {
        $configModels = config('typegen.models', ['include' => []]);
        return isset($configModels['include'][0]) && $configModels['include'][0] === '*';
    }

    protected function loadModels(): void
    {
        if ($this->shouldUseAllModels()) {
            $this->info('Including all models...');
            $this->models = collect(app('files')->files(app_path('Models')))
                ->map(
                    fn($file) => app()->getNamespace() . 'Models\\' .
                        Str::replaceLast('.php', '', str_replace('/', '\\', $file->getRelativePathname()))
                );
        } else {
            $this->info('Including specified models...');
            $configModels = config('typegen.models', ['include' => []]);
            $this->models = collect($configModels['include']);
        }
    }

    protected function getSelectedModels(): Collection
    {
        $modelNames = $this->models->map(fn($model) => class_basename($model))->toArray();
        $selectedNames = multiselect('Select the models to generate:', $modelNames);

        $this->line('Selected models:');
        return $this->models->filter(function ($model) use ($selectedNames) {
            $className = class_basename($model);
            $selected = in_array($className, $selectedNames);
            if ($selected) {
                $this->line("    $className");
            }
            return $selected;
        });
    }

    protected function getTableColumns(string $tableName): Collection
    {
        return collect(DB::select(
            "
            SELECT column_name, data_type, is_nullable
            FROM information_schema.columns 
            WHERE table_schema = 'public' AND table_name = :tableName",
            ['tableName' => $tableName]
        ));
    }

    protected function generateTypeDefinitions(): void
    {
        $this->line('Generating TypeScript interfaces...');
        $modelNames = [];

        $interfaces = $this->selectedModels->map(function ($modelClass) use (&$modelNames) {
            $model = app()->make($modelClass);
            $className = class_basename($model);
            $this->info("Generating interface for $className...");

            $modelNames[] = $className;
            $columns = $this->getTableColumns($model->getTable());
            $properties = $this->generateInterfaceProperties($columns);

            return "export interface $className {\n$properties\n}";
        })->implode("\n\n");

        // Add union type for dynamic indexing
        $unionTypes = implode(' | ', $modelNames);
        $interfaces .= "\n\nexport type ModelTypes = $unionTypes;";

        app('files')->put($this->typesOutputPath, $interfaces);
    }

    protected function generateInterfaceProperties(Collection $columns): string
    {
        return $columns->map(function ($column) {
            $db_type = $column->data_type;
            $type = config("typegen.mapping.$db_type", 'any');
            $attributeString = $column->is_nullable === 'YES' ? "$column->column_name?" : "$column->column_name";
            $typeEntry = "    $attributeString: $type;";

            if (Str::endsWith($column->column_name, '_id')) {
                $relationship = Str::beforeLast($column->column_name, '_id');
                $relatedModel = $this->findRelatedModel($relationship);
                if ($relatedModel) {
                    $relatedType = class_basename($relatedModel);
                    $this->line("    Found relationship: $relatedType");
                    $typeEntry .= "\n    $relationship?: $relatedType;";
                }
            }

            $this->line("    Inserting type: $typeEntry from $db_type");
            return $typeEntry;
        })->implode("\n");
    }

    protected function generateModelClasses(): void
    {
        $this->line('Generating JavaScript model classes...');

        collect([ModelType::BASE, ModelType::MODEL])->each(
            fn($modelType) => $this->generateModelClassesByType($modelType)
        );
    }

    protected function generateModelClassesByType(ModelType $modelType): void
    {
        $this->selectedModels->each(function ($modelClass) use ($modelType) {
            $model = app()->make($modelClass);
            $className = class_basename($model);
            $this->info("Generating class for {$className}Base...");

            $columns = $this->getTableColumns($model->getTable());
            $imports = $this->generateImports($columns, $className, $modelType);
            if ($modelType === ModelType::BASE) {
                $properties = $this->generateClassProperties($columns);
                $constructor = $this->generateConstructor($columns, $className);
            } else {
                $properties = "";
                $constructor = "";
            }

            $this->writeModelFile($className, $imports, $properties, $constructor, $modelType);
        });
    }

    protected function generateImports(Collection $columns, string $className, ModelType $modelType): string
    {
        $imports = "";
        if ($modelType === ModelType::BASE) {
            $imports .= "import { ";
            $columns->each(function ($column) use (&$imports) {
                if (Str::endsWith($column->column_name, '_id')) {
                    $relationship = Str::beforeLast($column->column_name, '_id');
                    $relatedModel = $this->findRelatedModel($relationship);
                    if ($relatedModel) {
                        $relatedType = class_basename($relatedModel);
                        $this->line("    Found relationship: $relatedType");
                        $imports .= "$relatedType, ";
                    }
                }
            });
            $imports .= "$className } from '\$models';";
        } else {
            $imports = "import { {$className}Base } from './_base/{$className}Base';";
        }
        return $imports;
    }

    protected function generateClassProperties(Collection $columns): string
    {
        return $columns->map(function ($column) {
            $type = config("typegen.mapping.{$column->data_type}", 'any');
            $attributeName = $column->is_nullable === 'YES' ? "{$column->column_name}?" : $column->column_name;
            $typeEntry = "    $attributeName: $type;";

            if (Str::endsWith($column->column_name, '_id')) {
                $relationship = Str::beforeLast($column->column_name, '_id');
                $relatedModel = $this->findRelatedModel($relationship);
                if ($relatedModel) {
                    $relatedType = class_basename($relatedModel);
                    $typeEntry .= "\n    $relationship?: $relatedType;";
                }
            }

            return $typeEntry;
        })->implode("\n");
    }

    protected function generateConstructor(Collection $columns, string $className): string
    {
        $constructorBody = $columns->map(function ($column) {
            return "        this.{$column->column_name} = data.{$column->column_name};";
        })->implode("\n");

        return "\n    constructor(data: $className) {\n$constructorBody\n    }";
    }

    protected function findRelatedModel(string $relationship): ?string
    {
        return $this->models->first(function ($model) use ($relationship) {
            return class_basename($model) === Str::studly($relationship);
        });
    }

    protected function writeModelFile(string $className, string $imports, string $properties, string $constructor, ModelType $modelType): void
    {
        $outputDir = $this->outputDirs[$modelType->value];
        $modelName = match ($modelType) {
            ModelType::BASE => "{$className}Base",
            ModelType::MODEL => "{$className}",
        };
        $implementsString = match ($modelType) {
            ModelType::BASE => "implements {$className}",
            ModelType::MODEL => "extends {$className}Base",
        };
        $contents = <<<TS
$imports

export class {$modelName} {$implementsString} {
$properties

$constructor
}
TS;

        file_put_contents("{$outputDir}{$modelName}.ts", $contents);
    }
}
