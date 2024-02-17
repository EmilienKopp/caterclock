<?php

namespace App\Adapters;

use Laravel\Socialite\Contracts\User as SocialiteUser;
use App\Contracts\OAuthUserInterface;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class GoogleUser implements OAuthUserInterface
{
    protected SocialiteUser $user;

    public function __construct(SocialiteUser $user)
    {
        $this->user = $user;
    }

    public function getProvider()
    {
        return 'google';
    }

    public function getId()
    {
        return $this->user->getId();
    }

    public function getName()
    {
        return $this->user->getName();
    }

    public function getEmail()
    {
        return $this->user->getEmail();
    }

    public function getAvatar()
    {
        return $this->user->getAvatar();
    }

    public function getAccessToken()
    {
        return $this->user->token;
    }

    public function getRefreshToken()
    {
        return $this->user->refreshToken;
    }

    public function getExpiresIn()
    {
        return $this->user->expiresIn;
    }
}