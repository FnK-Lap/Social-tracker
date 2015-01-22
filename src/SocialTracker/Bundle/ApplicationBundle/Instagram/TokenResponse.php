<?php

namespace SocialTracker\Bundle\ApplicationBundle\Instagram;

class TokenResponse
{
    public $accessToken;
    public $id;
    public $username;

    public function __construct($accessToken, $id, $username)
    {
        $this->accessToken = $accessToken;
        $this->id = $id;
        $this->username = $username;
    }
}