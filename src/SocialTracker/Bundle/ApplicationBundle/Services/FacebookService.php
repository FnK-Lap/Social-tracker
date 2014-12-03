<?php

namespace SocialTracker\Bundle\ApplicationBundle\Services;

use Facebook\FacebookSession;
use Facebook\FacebookRedirectLoginHelper;
use Facebook\FacebookRequestException;
use Facebook\GraphUser;
use Facebook\FacebookRequest;

class FacebookService
{
    private $clientId;
    private $clientSecret;
    private $client;
    private $session;

    public function __construct($clientId, $clientSecret, $client, $session)
    {
        $this->clientId     = $clientId;
        $this->clientSecret = $clientSecret;
        $this->client   = $client;
        $this->session  = $session;
        FacebookSession::setDefaultApplication($clientId, $clientSecret);
    }

    public function getAuthorizeUrl()
    {
        $helper = new FacebookRedirectLoginHelper('http://social-tracker.dev/settings?social=facebook');
        return $helper->getLoginUrl();
    }

    public function getAccessToken()
    {
        $helper = new FacebookRedirectLoginHelper('http://social-tracker.dev/settings?social=facebook');

        try 
        {
            $session = $helper->getSessionFromRedirect();
        } 
        catch(FacebookRequestException $ex) 
        {
            return array(
                'code' => $ex->getCode(),
                'message' => $ex->getMessage()
            );
        } 
        catch(\Exception $ex) 
        {
            return array(
                'code' => $ex->getCode(),
                'message' => $ex->getMessage()
            );
        }

        if ($session) {
            try 
            {
                $user_profile = (new FacebookRequest($session, 'GET', '/me'))->execute()->getGraphObject(GraphUser::className());
                $this->session->set('facebook', array(
                    'session' => $session,
                    'username' => $user_profile->getName()
                ));
            } 
            catch(FacebookRequestException $e) 
            {
                return array(
                    'code' => $ex->getCode(),
                    'message' => $ex->getMessage()
                );
            }  

            return array(
                'code' => 200,
            );
        }
    }
}