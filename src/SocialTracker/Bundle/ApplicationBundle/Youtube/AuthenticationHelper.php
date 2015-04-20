<?php

namespace SocialTracker\Bundle\ApplicationBundle\Youtube;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Guzzle\Service\Client;

class AuthenticationHelper
{
    const SCOPE = 'https://www.googleapis.com/auth/youtube.readonly';

    private     $clientId;
    private     $clientSecret;
    private     $guzzleClient;
    private     $googleClient;
    protected   $urlGenerator;

    public function __construct($clientId, $clientSecret, $guzzleClient, UrlGeneratorInterface $urlGenerator)
    {
        $this->clientId     = $clientId;
        $this->clientSecret = $clientSecret;
        $this->guzzleClient = $guzzleClient;
        $this->urlGenerator = $urlGenerator;

        $googleClient = new \Google_Client();
        $googleClient->setClientId($clientId);
        $googleClient->setClientSecret($clientSecret);
        $googleClient->setRedirectUri($this->getRedirectUri());
        $googleClient->setScopes(self::SCOPE);
        $googleClient->setAccessType('offline');

        $this->googleClient = $googleClient;
    }

    public function getAuthorizeUrl()
    {
        return $this->googleClient->createAuthUrl();
    }

    public function exchangeAuthorizationCode($code)
    {
        try{
            $this->googleClient->authenticate($code);
        } catch (\Google_Auth_Exception $e){
            return array(
                'code'    => $e->getCode(),
                'message' => $e->getMessage()
            );
        }
        $accessToken = $this->googleClient->getAccessToken();
        $service = new \Google_Service_Youtube($this->googleClient);
        $params = array(
            'mine' => true,
            'fields' => 'items/snippet/title'
        );

        $username = $service->channels->listChannels('snippet', $params)['items'][0]['snippet']['title'];

        return new TokenResponse(
            $accessToken,
            $username
        );
    }

    public function getGoogleClient()
    {
        return $this->googleClient;
    }

    private function getRedirectUri()
    {
        return $this->urlGenerator->generate('settings_youtube_callback', array(), UrlGeneratorInterface::ABSOLUTE_URL);
    }
}