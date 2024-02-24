<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class typegen extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:typegen';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate TypeScript types from Laravel models.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dbDriver = config('database.default');

        if($dbDriver === 'pgsql') {
            $this->info('Using PostgreSQL database driver.');
        } else {
            $this->error('Only PostgreSQL is supported at the moment.');
            return;
        }
        
        $models = config('typegen.models');
        if($models['include'][0] === '*') {
            $this->info('Including all models...');
            // Read the content of the app/Models directory
            $models = collect(app('files')->files(app_path('Models')))->map(function ($file) {
                // Convert the file path to a class name
                return app()->getNamespace() . 'Models\\' . str_replace(['/', '.php'], ['\\', ''], $file->getRelativePathname());
            });
        } else {
            $this->info('Including specified models...');
            $models = collect($models['include']);
        }


        $output = config('typegen.output');

        $this->line('Generating TypeScript types...');
        $modelTypesArray = [];

        $types = $models->map(function ($model) use ($models,&$modelTypesArray) {
            $model = app()->make($model);
            $className = class_basename($model);
            $this->info("Generating type for $className...");

            //TODO: Make the DBAL introspection work to leverage the Eloquent ORM abstraction

            // $columns = DB::connection($model->getConnectionName())->getDoctrineSchemaManager()->listTableColumns($model->getTable());
            // Encountering issues as of 2024/02/18: 

            /**
             * Declaration of Illuminate\Database\PDO\Concerns\ConnectsToDatabase::connect(array $params, $username = null, $password = null, array $driverOptions = []) 
             * must be compatible with Doctrine\DBAL\Driver::connect(array $params): Doctrine\DBAL\Driver\Connection
             */

            $columns = DB::select("
                SELECT column_name, data_type, is_nullable
                FROM information_schema.columns 
                WHERE table_schema = 'public' AND table_name = :tableName", 
                ['tableName' => $model->getTable()]
            );

            logger($columns);

            $properties = collect($columns)->map(function ($column) use ($models) {

                $db_type = $column->data_type;
                $type = config("typegen.mapping.$db_type", 'any');
                $attributeString = $column->is_nullable === 'YES' ? "$column->column_name?" : "$column->column_name";
                
                $typeEntryString = "    $attributeString: $type;";

                // If the column is a foreign key (_id), we'll assume it's a relationship for now:
                if (str_ends_with($column->column_name, '_id')) {
                    $relationship = str_replace('_id', '', $column->column_name);
                    // Get the associated model name
                    $relatedModel = $models->first(function ($model) use ($relationship) {
                        //Convert something_like_this to SomethingLikeThis
                        $capitalized = str_replace(' ', '', ucwords(str_replace('_', ' ', $relationship)));
                        return class_basename($model) === $capitalized;
                    });
                    if($relatedModel) {
                        $related = app()->make($relatedModel);
                        $relatedType = class_basename($related);
                        $this->line("    Found relationship: $relatedType");
                        $typeEntryString .= "\n    $relationship?: $relatedType;";
                    }
                }

                $this->line("    Inserting type: $typeEntryString");
                return $typeEntryString;
            })->implode("\n");

            $modelTypesArray[] = $className;

            return "export type $className = {\n$properties\n} & {[key:string]: any};\n";
        })->implode("\n\n");

        //Replace the 'any' in & {[key:string]: any}; with an exploded array of the model names from the $modelTypesArray
        $unionTypes = implode(' | ', $modelTypesArray);
        $types = str_replace('& {[key:string]: any}', "& {[key:string]: $unionTypes}", $types);

        app('files')->put($output, $types);

        $this->info('TypeScript types generated successfully.');
    }
}
