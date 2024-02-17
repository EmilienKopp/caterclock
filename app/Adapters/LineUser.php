<?php

namespace App\Adapters;

use App\Contracts\OAuthUserInterface;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class LineUser implements OAuthUserInterface
{
    protected $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

    public function getProvider()
    {
        return 'line';
    }

    public function getId()
    {
        return $this->user->sub ?? $this->user['sub'];
    }

    public function getName()
    {
        return $this->user->name ?? $this->user['name'];
    }

    public function getEmail()
    {
        return $this->user->email ?? $this->user['email'];
    }

    public function getAvatar()
    {
        return $this->user->picture ?? $this->user['picture'];
    }

    public function getAccessToken()
    {
        
    }

    public function getRefreshToken()
    {
        
    }

    public function getExpiresIn()
    {
        
    }
}