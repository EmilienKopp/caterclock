<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    // const SUPERADMIN = Role::name('superadmin')->id;
    // const ADMIN = Role::name('admin')->id;
    // const EMPLOYEE = Role::name('employee')->id;
    // const OWNER = Role::name('owner')->id;
    // const MANAGER = Role::name('manager')->id;
    // const FREELANCER = Role::name('freelancer')->id;

    public static function of(string $name) {
        return static::where('name', $name)->first();
    }

    public static function id(string $name) {
        return static::where('name', $name)->first()->id;
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }


    public function permissions()
    {
        return $this->hasMany(Permission::class);
    }
}
