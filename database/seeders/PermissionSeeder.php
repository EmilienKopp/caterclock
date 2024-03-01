<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

function populate($role, $permissions)
{
    foreach ($permissions as $permission) {
        $model = key($permission);
        $abilities = $permission[$model];
        foreach ($abilities as $ability) {
            $permission = \App\Models\Permission::upsert([
                'model' => $model,
                'role_id' => $role->id,
                'ability' => $ability,
            ], ['model', 'role_id', 'ability']);
        }
    }
}

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $superadmin = \App\Models\Role::where('name', 'super-admin')->first();
        $admin = \App\Models\Role::where('name', 'admin')->first();
        $employee = \App\Models\Role::where('name', 'employee')->first();
        $manager = \App\Models\Role::where('name', 'manager')->first();
        $owner = \App\Models\Role::where('name', 'owner')->first();
        $freelancer = \App\Models\Role::where('name', 'freelancer')->first();

        $superadminPermissions = [
            ['App\\Models\\User' => ['create', 'index', 'show', 'update', 'delete']],
            ['App\\Models\\Role' => ['create', 'index', 'show', 'update', 'delete']],
            ['App\\Models\\Permission' => ['create', 'index', 'show', 'update', 'delete']],
            ['App\\Models\\Project' => ['create', 'index', 'show', 'update', 'delete']],
            ['App\\Models\\TimeLog' => ['create', 'index', 'show', 'update', 'delete']],
            ['App\\Models\\Company' => ['create', 'index', 'show', 'update', 'delete']],
            ['App\\Models\\ConnectionRequest' => ['index', 'show','create', 'update', 'delete']],
        ];
        populate($superadmin, $superadminPermissions);

        $adminPermissions = [
            ['App\\Models\\User' => ['create', 'index', 'show', 'update']],
            ['App\\Models\\Role' => ['index', 'show']],
            ['App\\Models\\Permission' => ['index', 'show']],
            ['App\\Models\\Project' => ['create', 'index', 'show', 'update', 'delete']],
            ['App\\Models\\TimeLog' => ['create', 'index', 'show', 'update', 'delete']],
            ['App\\Models\\Company' => ['create', 'index', 'show', 'update', 'delete']],
            ['App\\Models\\ConnectionRequest' => ['index', 'show','create', 'update', 'delete']],
        ];
        populate($admin, $adminPermissions);

        $employeePermissions = [
            ['App\\Models\\User' => ['index', 'show']],
            ['App\\Models\\Project' => ['index', 'show']],
            ['App\\Models\\TimeLog' => ['create','index', 'show']],
            ['App\\Models\\Company' => ['index', 'show']],
            ['App\\Models\\ConnectionRequest' => ['index', 'show','create', 'update', 'delete']],
        ];
        populate($employee, $employeePermissions);


        $managerPermissions = [
            ['App\\Models\\User' => ['index', 'show']],
            ['App\\Models\\Project' => ['index', 'show', 'create', 'update', 'delete']],
            ['App\\Models\\TimeLog' => ['index', 'show', 'create', 'update', 'delete']],
            ['App\\Models\\Company' => ['index', 'show', 'update']],
            ['App\\Models\\ConnectionRequest' => ['index', 'show','create', 'update', 'delete']],
        ];
        populate($manager, $managerPermissions);

        $ownerPermissions = [
            ['App\\Models\\User' => ['index', 'show']],
            ['App\\Models\\Project' => ['index', 'show', 'create', 'update', 'delete']],
            ['App\\Models\\TimeLog' => ['index', 'show', 'create', 'update', 'delete']],
            ['App\\Models\\Company' => ['index', 'show', 'create', 'update', 'delete']],
            ['App\\Models\\ConnectionRequest' => ['index', 'show','create', 'update', 'delete']],
        ];
        populate($owner, $ownerPermissions);

        $freelancerPermissions = [
            ['App\\Models\\User' => ['index', 'show']],
            ['App\\Models\\Project' => ['index', 'show', 'create', 'update', 'delete']],
            ['App\\Models\\TimeLog' => ['index', 'show', 'create', 'update', 'delete']],
            ['App\\Models\\Company' => ['index', 'show']],
            ['App\\Models\\ConnectionRequest' => ['index', 'show','create', 'update', 'delete']],
        ];
        populate($freelancer, $freelancerPermissions);
    }
}
