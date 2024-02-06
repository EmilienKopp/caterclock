<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];


    public function can($abilities, $arguments = []) {
        if(!is_array($abilities)) {
            $abilities = [$abilities];
        }
        if(!is_array($arguments)) {
            $arguments = [$arguments];
        }
        $roles = $this->roles;
        $can = false;
        foreach($roles as $role) {
            foreach($abilities as $ability) {
                $permission = $role->permissions()
                    ->where('ability', $ability)
                    ->whereIn('model', $arguments)
                    ->first();
                if($permission) {
                    $can = true;
                }
            }
        }
        return $can;
    }

    public function isRelatedTo($model) {
        switch(true) {
            case $model instanceof Company:
                return $this->companies->contains($model);
            case $model instanceof Project:
                return $this->getInvolvedProjects()->contains($model);
            case $model instanceof User:
                return $this->id === $model->id;
        }
        return false;
    }

    public function owns($model) {
        switch(true) {
            case $model instanceof Company:
                return $this->ownedCompanies->contains($model);
            case $model instanceof TimeLog:
                return $this->id == $model->user_id 
                    || $this->owns($model->project);
            case $model instanceof Project:
                return $model->user_id == $this->id
                    || $this->ownedCompanies->contains($model->company);
        }
        return false;
    }

    public function roles() {
        return $this->belongsToMany(Role::class);
    }

    public static function authenticated() {
        return User::with(['roles','companies'])->find(auth()->id());
    }

    public function companies() {
        return $this->belongsToMany(Company::class)
            ->as('position')
            ->withPivot('role_id')
            ->withTimestamps();
    }

    public function ownedCompanies() {
        return $this->companies()
            ->wherePivot('role_id', Role::where('name', 'owner')->first()->id)
            ->orderByPivot('created_at', 'desc');
    }

    public function employingCompanies() {
        return $this->companies()
            ->wherePivot('role_id', Role::where('name', 'owner')->first()->id)
            ->orderByPivot('created_at', 'desc');
    }

    public function contractedCompanies() {
        return $this->companies()
            ->wherePivot('role_id', Role::where('name', 'freelancer')->first()->id)
            ->orderByPivot('created_at', 'asc');
    }

    public function managedCompanies() {
        return $this->companies()
            ->wherePivot('role_id', Role::where('name', 'manager')->first()->id)
            ->orderByPivot('created_at', 'desc');
    }

    public function sentConnectionRequests() {
        return $this->hasMany(ConnectionRequest::class, 'sender_id')
            ->with(['company','sender','receiver','role']);
    }

    public function projects() {
        return $this->hasMany(Project::class);
    }

    public function getInvolvedProjects() {
        // Get projects from companies where the user has a certain position
        $roles = Role::whereIn('name', ['owner', 'admin', 'employee', 'freelancer'])->get();
        $companyProjects = Project::whereHas('company', function ($query) use ($roles) {
            $query->whereHas('users', function ($subQuery) use ($roles) {
                $subQuery->where('user_id', $this->id)
                        ->whereIn('role_id', $roles->pluck('id'));
            });
        })->get();

        // Get projects owned by the user
        $ownedProjects = $this->projects;

        return $companyProjects->merge($ownedProjects)->unique('id');
    }
}
