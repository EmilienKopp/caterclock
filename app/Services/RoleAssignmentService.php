<?php

namespace App\Services;

use App\Models\Role;
use App\Models\User;
use App\Models\Company;
use Illuminate\Http\Request;


class RoleAssignmentService
{
    public static function assignRole(User $user, Request $request): void
    {
        if($request->register_type === 'hire' || $request->register_type === 'both') {
            $request->validate([
                'company_name' => 'required|string|max:255',
            ]);

            $company = Company::create([
                'name' => $request->company_name,
                'contact_email' => $request->email,
                'representative_id' => $user->id,
            ]);

            $ownerRole = Role::where('name', 'owner')->first();

            $company->users()->attach($user->id, ['role_id' => $ownerRole->id]);
        } else if($request->register_type === 'both' || $request->register_type === 'work') {
            $roleName = $request->role_name ?? 'employee';
            $role = Role::where('name', $roleName)->first();

            $user->roles()->attach($role->id);
        }
    }
}