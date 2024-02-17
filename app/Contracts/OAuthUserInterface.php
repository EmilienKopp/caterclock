<?php

namespace App\Contracts;

interface OAuthUserInterface
{
    public function getId();
    public function getName();
    public function getEmail();
    public function getAvatar();
    public function getProvider();
    public function getAccessToken();
    public function getRefreshToken();
    public function getExpiresIn();
}
