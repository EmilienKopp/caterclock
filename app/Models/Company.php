<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function positions()
    {
        return $this->hasMany(Position::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class)->withPivot('role_id')->withTimestamps();
    }

    public function owner()
    {
        return $this->users()->wherePivot('role_id', Role::where('name', 'owner')->first()->id);
    }

    public function employees()
    {
        return $this->users()->wherePivot('role_id',Role::where('name', 'employee')->first()->id);
    }

    public function hiredFreelancers()
    {
        return $this->users()->wherePivot('role_id', Role::where('name', 'freelancer')->first()->id);
    }

    public function admins()
    {
        return $this->users()->wherePivot('role_id', Role::where('name', 'admin')->first()->id);
    }

    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    public function connectionRequests()
    {
        return $this->hasMany(ConnectionRequest::class)
            ->with(['sender', 'receiver', 'company']);
    }
}
