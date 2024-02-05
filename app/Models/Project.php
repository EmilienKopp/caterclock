<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable=[
        'name',
        'company_id',
        'user_id',
        'description',
        'start_date',
        'end_date',
    ];


    public static function getAllRelated($user) {
        $companies = $user->companies;
        $projects = $companies->map(function($company) {
            return $company->projects()->with('company')->get();
        })->flatten();

        return $projects;
    }

    public static function getOwnedByAuthUser(): Collection
    {
        return Project::where('user_id', auth()->user()->id)
                        ->get();
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function timeLogs()
    {
        return $this->hasMany(TimeLog::class);
    }

    public function tasks()
    {
        // return $this->hasMany(Task::class);
    }

}
