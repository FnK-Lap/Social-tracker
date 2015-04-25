<?php

namespace SocialTracker\Bundle\ApplicationBundle\Facebook;

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