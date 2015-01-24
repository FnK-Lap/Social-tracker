<?php

namespace SocialTracker\Bundle\ApplicationBundle\Facebook;

use Facebook\FacebookSession;
use Facebook\FacebookRedirectLoginHelper;
use Facebook\FacebookRequest;
use Facebook\FacebookRequestException;
use Facebook\GraphUser;
use Guzzle\Service\Client;
use igorw;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\Router;

class AuthenticationHelper
{
    const SCOPES = array('read_stream', 'publish_actions', 'user_friends');

    private $clientId;
    private $clientSecret;
    private $guzzleClient;
    private $urlGenerator;

    public function __construct($clientId, $clientSecret, Client $guzzleClient, UrlGeneratorInterface $urlGenerator, $loginHelper)
    {
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->guzzleClient = $guzzleClient;
        $this->urlGenerator = $urlGenerator;
        $this->loginHelper  = $loginHelper;
        FacebookSession::setDefaultApplication($clientId, $clientSecret);
        FacebookSession::enableAppSecretProof(false);
    }

    private function createRequest(FacebookSession $facebookSession, $method, $path, $parameters = null, $version = null, $etag = null)
    {
        $request = new FacebookRequest($facebookSession, $method, $path, $parameters, $version, $etag);
        $clientHandler = new GuzzleClientHandler($this->guzzleClient);
        $request::setHttpClientHandler($clientHandler);

        return $request;
    }

    public function getAuthorizeUrl()
    {
        return $this->loginHelper->getLoginUrl(array(
            'scope' => implode(', ', self::SCOPES)
        ));
    }

    public function exchangeAuthorizationCode()
    {
        try {
            $session = $this->loginHelper->getSessionFromRedirect();
        } catch(FacebookRequestExecption $ex) {
            return array(
                'code' => $ex->getCode(),
                'message' => $ex->getMessage()
            );
        } catch(\Exception $ex) {
            return array(
                'code' => $ex->getCode(),
                'message' => $ex->getMessage()
            );
        }

        if ($session) {
            try {
                $user_profile = $this->createRequest($session, 'GET', '/me')->execute()->getGraphObject(GraphUser::className());
            } catch(FacebookRequestException $e) {
                return array(
                    'code' => $e->getCode(),
                    'message' => $e->getMessage()
                );
            }  

            // Get long-lived accessToken
            $accessToken = $session->getAccessToken();

            // $longLivedAccessToken = $accessToken->extend($this->clientId, $this->clientSecret);
            return new TokenResponse(
                $accessToken,
                $user_profile->getName()
            );

        }
        return null;
    }

    private function getRedirectUri()
    {
        return $this->urlGenerator->generate('settings_instagram_callback', array(), UrlGeneratorInterface::ABSOLUTE_URL);
    }
}
