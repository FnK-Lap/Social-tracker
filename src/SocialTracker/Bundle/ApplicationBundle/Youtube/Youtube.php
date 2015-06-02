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

    public function getUserHomeActivities($accessToken, $prevData = null)
    {
        $this->googleClient->setAccessToken($accessToken);

        if ($this->googleClient->isAccessTokenExpired()) {
            return array(
                'code' => 400,
                'message' => 'Token expired'
            );
        }

        $youtube   = new \Google_Service_Youtube($this->googleClient);
        $optParams = array(
            'home'       => true,
            'maxResults' => 5,
            'publishedAfter' => '2015-04-28T16:00:37.000Z'
        );

        try {
            $result = $youtube->activities->listActivities('id, snippet', $optParams);
        } catch (\Google_Auth_Exception $e) {
            return array(
                'code' => $e->getCode(),
                'message' => $e->getMessage()
            );
        }

        foreach ($result->getItems() as $key => $value) {
            var_dump($value['snippet']['publishedAt']);
        }
        die;
        if ($result->nextPageToken) {

        }

        // var_dump($result);

    }
}