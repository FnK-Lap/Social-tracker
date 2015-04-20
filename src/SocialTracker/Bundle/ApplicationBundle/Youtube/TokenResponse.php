<?php

namespace SocialTracker\Bundle\ApplicationBundle\Youtube;

class TokenResponse
{
    public $accessToken;
    public $username;

    public function __construct($accessToken, $username)
    {
        $this->accessToken = $accessToken;
        $this->username = $username;
    }
}