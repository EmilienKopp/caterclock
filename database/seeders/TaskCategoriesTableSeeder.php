<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TaskCategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Coding', 'description' => 'Development and programming tasks.'],
            ['name' => 'Testing', 'description' => 'Testing and quality assurance tasks.'],
            ['name' => 'Documentation', 'description' => 'Writing and maintaining documentation.'],
            ['name' => 'Meeting', 'description' => 'Team meetings and discussions.'],
            ['name' => 'Design', 'description' => 'UI/UX design and graphic design tasks.'],
            ['name' => 'Code Review', 'description' => 'Reviewing code written by peers.'],
            ['name' => 'Deployment', 'description' => 'Deployment and continuous integration tasks.'],
            ['name' => 'Research', 'description' => 'Research and exploration of new technologies.'],
            ['name' => 'Planning', 'description' => 'Planning and project management tasks.'],
            ['name' => 'Bug Fixing', 'description' => 'Fixing bugs and issues.'],
            ['name' => 'Refactoring', 'description' => 'Refactoring and code cleanup tasks.'],
            ['name' => 'Training', 'description' => 'Training and mentoring tasks.'],
            ['name' => 'Support', 'description' => 'Support and maintenance tasks.'],
            ['name' => 'Other', 'description' => 'Other tasks.'],
        ];

        DB::table('task_categories')->upsert($categories, ['name']);
    }
}
