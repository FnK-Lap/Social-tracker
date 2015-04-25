<?php

namespace SocialTracker\Bundle\ApplicationBundle\Instagram;

use Guzzle\Service\Client;
use igorw;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\Router;

class AuthenticationHelper
{
    const SCOPES = array('likes', 'comments');

    private $clientId;
    private $clientSecret;
    private $guzzleClient;
    private $urlGenerator;

    public function __construct($clientId, $clientSecret, Client $guzzleClient, UrlGeneratorInterface $urlGenerator)
    {
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->guzzleClient = $guzzleClient;
        $this->urlGenerator = $urlGenerator;
    }

    public function getAuthorizeUrl()
    {
        $queryString = http_build_query(array(
            'client_id' => $this->clientId,
            'redirect_uri' => $this->getRedirectUri(),
            'response_type' => 'code',
            'scope' => implode(' ', self::SCOPES)
        ));

        return sprintf('https://api.instagram.com/oauth/authorize/?%s', $queryString);
    }

    public function exchangeAuthorizationCode($code)
    {
        $data = array(
            'client_id'     => $this->clientId,
            'client_secret' => $this->clientSecret,
            'grant_type'    => 'authorization_code',
            'redirect_uri'  => $this->getRedirectUri(),
            'code'          => $code
        );

        $request = $this->guzzleClient->post('https://api.instagram.com/oauth/access_token', null, $data);
        $response = $request->send()->json();

        return new TokenResponse(
            igorw\get_in($response, ['access_token']),
            igorw\get_in($response, ['user', 'id']),
            igorw\get_in($response, ['user', 'username'])
        );
    }

    private function getRedirectUri()
    {
        return $this->urlGenerator->generate('settings_instagram_callback', array(), UrlGeneratorInterface::ABSOLUTE_URL);
    }
}
