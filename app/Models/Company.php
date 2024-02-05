<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function users()
    {
        return $this->belongsToMany(User::class)->withPivot('position')->withTimestamps();
    }

    public function owner()
    {
        return $this->users()->wherePivot('position', 'owner');
    }

    public function employees()
    {
        return $this->users()->wherePivot('position', 'employee');
    }

    public function hiredFreelancers()
    {
        return $this->users()->wherePivot('position', 'hired_freelance');
    }

    public function admins()
    {
        return $this->users()->wherePivot('position', 'admin');
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
