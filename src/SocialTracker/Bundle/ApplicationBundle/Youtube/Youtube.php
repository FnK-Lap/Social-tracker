<?php

namespace SocialTracker\Bundle\ApplicationBundle\Youtube;

use Guzzle\Service\Client;
use SocialTracker\Bundle\ApplicationBundle\Youtube\AuthenticationHelper;

class Youtube
{
    private $guzzleClient;
    private $helper;
    private $googleClient;

    public function __construct(Client $guzzleClient, AuthenticationHelper $helper)
    {
        $this->guzzleClient = $guzzleClient;
        $this->helper       = $helper;
        $this->googleClient = $helper->getGoogleClient();
    }

    public function getUserFeed($accessToken)
    {
        $this->googleClient->setAccessToken($accessToken);
        var_dump($this->googleClient);
    }
}