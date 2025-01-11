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
    protected $currentModelClass;
    protected $uiFramework;

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

        $frameworks = ['Svelte 4', 'Svelte 5'];
        //Prompt for UI Framework
        $uiFramework = select('Select the UI Framework:', $frameworks);
        $this->info("UI Framework selected: {$uiFramework}");

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
            $this->currentModelClass = $modelClass;
            $this->info("Generating interface for $className...");

            $modelNames[] = $className;
            $columns = $this->getTableColumns($model->getTable());
            $properties = $this->generateInterfaceProperties($columns);

            return "export interface I{$className} {\n$properties\n}";
        })->implode("\n\n");

        // Add union type for dynamic indexing
        $unionTypes = implode(' | ', $modelNames);
        $interfaces .= "\n\nexport type ModelTypes = $unionTypes;";

        app('files')->put($this->typesOutputPath, $interfaces);
    }

    protected function generateInterfaceProperties(Collection $columns): string
    {
        $model = $this->getCurrentModel();
        $hasManyRelations = $this->findHasManyRelationships(get_class($model), $model->getTable());

        $properties = $columns->map(function ($column) {
            $db_type = $column->data_type;
            $type = config("typegen.mapping.$db_type", 'any');
            $attributeString = $column->is_nullable === 'YES' ? "$column->column_name?" : "$column->column_name";
            $typeEntry = "    $attributeString: $type;";

            if (Str::endsWith($column->column_name, '_id')) {
                $relationship = Str::beforeLast($column->column_name, '_id');
                $relatedModel = $this->findRelatedModel($relationship);
                if ($relatedModel) {
                    $relatedType = class_basename($relatedModel);
                    $this->line("    Found BelongsTo relationship: $relatedType");
                    $typeEntry .= "\n    $relationship?: $relatedType;";
                }
            }

            return $typeEntry;
        })->implode("\n");

        // Add HasMany relationships
        if ($hasManyRelations->isNotEmpty()) {
            $properties .= "\n";
            $hasManyRelations->each(function ($relation) use (&$properties) {
                $this->line("    Found HasMany relationship: {$relation['name']} -> {$relation['type']}[]");
                $properties .= "\n    {$relation['name']}?: {$relation['type']}[];";
            });
        }

        return $properties;
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
        if ($modelType === ModelType::MODEL) {
            $interfaceName = $this->classNameToInterfaceName($className);
            $frameworkSpecificPath = match ($this->uiFramework) {
                'Svelte 4' => "'./_base/{$className}Base';",
                'Svelte 5' => "'./_base/{$className}Base.svelte';",
                default => "'./_base/{$className}Base';",
            };
            return "import { {$className}Base } from {$frameworkSpecificPath};\n"
                . "import type { $interfaceName } from '\$models';";
        }

        $imports = new Collection();

        // Add the current model's interface
        $imports->push($className);

        // Add BelongsTo relationships from foreign keys
        $columns->each(function ($column) use ($imports) {
            if (Str::endsWith($column->column_name, '_id')) {
                $relationship = Str::beforeLast($column->column_name, '_id');
                $relatedModel = $this->findRelatedModel($relationship);
                if ($relatedModel) {
                    $relatedType = class_basename($relatedModel);
                    $imports->push($relatedType);
                }
            }
        });

        // Add HasMany relationships
        $model = $this->getCurrentModel();
        $hasManyRelations = $this->findHasManyRelationships(get_class($model), $model->getTable());
        $hasManyRelations->each(function ($relation) use ($imports) {
            $imports->push($relation['type']);
        });

        $imports = $imports->unique()
            ->map(fn($import) => $this->classNameToInterfaceName($import))
            ->sort()
            ->values();

        return "import type { " . $imports->implode(', ') . " } from '\$models';";
    }

    protected function generateClassProperties(Collection $columns): string
    {
        $model = $this->getCurrentModel();
        $hasManyRelations = $this->findHasManyRelationships(get_class($model), $model->getTable());

        $properties = $columns->map(function ($column) {
            $type = config("typegen.mapping.{$column->data_type}", 'any');
            $isNullable = $column->is_nullable === 'YES';
            $attributeName = $isNullable ? "{$column->column_name}?" : $column->column_name;
            $frameworkSpecificTypeDef = match ($this->uiFramework) {
                'Svelte 4' => ": $type;",
                'Svelte 5' => " = \$state<$type>();",
                default => $type,
            };
            $typeEntry = "    $attributeName: $type;";

            if (Str::endsWith($column->column_name, '_id')) {
                $relationship = Str::beforeLast($column->column_name, '_id');
                $relatedModel = $this->findRelatedModel($relationship);
                if ($relatedModel) {
                    $relatedType = class_basename($relatedModel);
                    $relatedInterfaceName = $this->classNameToInterfaceName($relatedType);
                    $typeEntry .= "\n    $relationship?: $relatedInterfaceName;";
                }
            }

            return $typeEntry;
        })->implode("\n");

        // Add HasMany relationships
        if ($hasManyRelations->isNotEmpty()) {
            $properties .= "\n";
            $hasManyRelations->each(function ($relation) use (&$properties) {
                $interfaceName = $this->classNameToInterfaceName($relation['type']);
                $properties .= "\n    {$relation['name']}?: {$interfaceName}[];";
            });
        }

        return $properties;
    }

    protected function generateConstructor(Collection $columns, string $className): string
    {
        $model = $this->getCurrentModel();
        $hasManyRelations = $this->findHasManyRelationships(get_class($model), $model->getTable());

        $constructorBody = $columns->map(function ($column) {
            return "        this.{$column->column_name} = data.{$column->column_name};";
        })->implode("\n");

        // Add HasMany relationships initialization
        if ($hasManyRelations->isNotEmpty()) {
            $constructorBody .= "\n";
            $hasManyRelations->each(function ($relation) use (&$constructorBody) {
                $constructorBody .= "\n        this.{$relation['name']} = data.{$relation['name']} ?? [];";
            });
        }

        return "\n    constructor(data: $className) {\n$constructorBody\n    }";
    }

    protected function findRelatedModel(string $relationship): ?string
    {
        return $this->models->first(function ($model) use ($relationship) {
            return class_basename($model) === Str::studly($relationship);
        });
    }

    protected function findHasManyRelationships(string $modelClass, string $tableName): Collection
    {
        // Get all foreign key constraints pointing to this table
        $foreignKeys = DB::select(
            "
        SELECT
            tc.table_name as child_table,
            kcu.column_name as foreign_key_column
        FROM information_schema.table_constraints AS tc 
        JOIN information_schema.key_column_usage AS kcu
            ON tc.constraint_name = kcu.constraint_name
            AND tc.table_schema = kcu.table_schema
        JOIN information_schema.constraint_column_usage AS ccu
            ON ccu.constraint_name = tc.constraint_name
            AND ccu.table_schema = tc.table_schema
        WHERE tc.constraint_type = 'FOREIGN KEY'
        AND ccu.table_name = :tableName
        AND tc.table_schema = 'public'",
            ['tableName' => $tableName]
        );

        $transformed = collect([]);
        $foreignKeys = collect($foreignKeys);
        foreach ($foreignKeys as $fk) {
            $found = $foreignKeys->first(function ($item) use ($fk) {
                return $item->child_table === $fk->child_table;
            });
            if ($found) {
                $before_id = Str::beforeLast($fk->foreign_key_column, '_id');
                $new = (object) [
                    'child_table' => "{$before_id}_{$fk->child_table}",
                    'foreign_key_column' => $fk->foreign_key_column,
                ];
                $transformed->push($new);
            }
            $transformed->push($fk);
        }

        return collect($transformed)->unique('child_table')->map(function ($fk) use ($modelClass) {
            // Convert the table name to a potential model name
            $childTable = Str::singular($fk->child_table);
            $relationshipName = Str::camel(Str::plural($childTable)); // products, orderItems, etc.

            $relatedModel = $this->findModelByTable($childTable);

            if ($relatedModel) {
                return [
                    'name' => $relationshipName,
                    'type' => class_basename($relatedModel),
                    'foreign_key' => $fk->foreign_key_column
                ];
            }

            return null;
        })->filter();
    }

    protected function findModelByTable(string $tableName): ?string
    {
        $modelName = Str::studly(Str::singular($tableName));

        return $this->models->first(function ($model) use ($modelName) {
            return class_basename($model) === $modelName;
        });
    }

    protected function getCurrentModel(): object
    {
        // This helper method should be called from within a context where $modelClass is available
        return app()->make($this->currentModelClass);
    }

    protected function writeModelFile(string $className, string $imports, string $properties, string $constructor, ModelType $modelType): void
    {
        $outputDir = $this->outputDirs[$modelType->value];
        $modelName = match ($modelType) {
            ModelType::BASE => "{$className}Base",
            ModelType::MODEL => "{$className}",
        };
        $interfaceName = $this->classNameToInterfaceName($className);
        $implementsString = match ($modelType) {
            ModelType::BASE => "implements {$interfaceName}",
            ModelType::MODEL => "extends {$className}Base implements {$interfaceName}",
        };

        // Add imports for array types if we have HasMany relationships
        if ($modelType === ModelType::BASE) {
            $model = $this->getCurrentModel();
            $hasManyRelations = $this->findHasManyRelationships(get_class($model), $model->getTable());
        }

        $contents = <<<TS
    $imports
    
    export class {$modelName} {$implementsString} {
    $properties
    
    $constructor
    }
    TS;

        file_put_contents("{$outputDir}{$modelName}.ts", $contents);
    }

    private function classNameToInterfaceName(string $className): string
    {
        return "I{$className}";
    }
}
