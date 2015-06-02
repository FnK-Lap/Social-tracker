<?php

namespace SocialTracker\Bundle\ApplicationBundle\Youtube;

class TokenResponse
{
    public $accessToken;
    public $refreshToken;
    public $username;

    public function __construct($accessToken, $refreshToken, $username)
    {
        $this->accessToken  = $accessToken;
        $this->refreshToken = $refreshToken;
        $this->username     = $username;
    }
}