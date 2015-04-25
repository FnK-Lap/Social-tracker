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

    public function getUserHomeActivities($accessToken)
    {
        $this->googleClient->setAccessToken($accessToken);

        $youtube = new \Google_Service_Youtube($this->googleClient);

        try {
            $result = $youtube->activities->listActivities('id', array('home' => true));
        } catch (\Google_Auth_Exception $e) {
            // Use refresh token
            return array(
                'code' => $e->getCode(),
                'message' => $e->getMessage()
            ));
        }
        var_dump($result);

    }
}