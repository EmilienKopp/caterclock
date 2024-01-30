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

    public function companies() {
        return $this->belongsToMany(Company::class)
            ->withPivot('position')->withTimestamps();
    }

    public function ownedCompanies() {
        return $this->companies()
            ->wherePivot('position', 'owner')
            ->orderByPivot('created_at', 'desc');
    }

    public function employingCompanies() {
        return $this->companies()
            ->wherePivot('position', 'employee')
            ->orderByPivot('created_at', 'desc');
    }

    public function contractedCompanies() {
        return $this->companies()
            ->wherePivot('position', 'hired_freelance')
            ->orderByPivot('created_at', 'asc');
    }

    public function managedCompanies() {
        return $this->companies()
            ->wherePivot('position', 'admin')
            ->orderByPivot('created_at', 'desc');
    }

    public function sentConnectionRequests() {
        return $this->hasMany(ConnectionRequest::class, 'sender_id')
            ->with(['company','sender','receiver']);
    }
}
