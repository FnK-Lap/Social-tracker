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
        $param = array(
            'scope' => 'read_stream, publish_actions, user_friends'
        );
        return $helper->getLoginUrl($param);
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
            die('cc');
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
                    'code' => $e->getCode(),
                    'message' => $e->getMessage()
                );
            }  

            return array(
                'code' => 200,
            );
        }
    }

    public function getUserFeed($params = array())
    {
        $url = 'me/home';

        $i = 0;
        foreach ($params as $param => $value) 
        {
            if ($i == 0) 
            {
                $url .= '?'.$param.'='.$value;
            }
            else
            {
                $url .= '&'.$param.'='.$value;
            }
            $i++;
        }
        unset($i);

        $request = new FacebookRequest(
        $this->session->get('facebook')['session'],
            'POST',
            '',
            array(
                'access_token' => 'CAANruVk8ynABALJZBm019R90D3HauVOZCHXJHmcuW5ehVyZC7fU0f6bzJdc205zJJ4w5JbzRelt5ewxg7vpaJ2NuXfcZBdlQl1fVSChZAkJc3b4M4ThcqANrVQUdLNIHNH1WGgT4VE1M4YFBOeVwSqWcDPn2fMfdMLn9YZCnn8qWKZBtKnJO7nFGbzOJo3tZAtXt8hMZCXIV3MORhBZB2wYjNE',
                'batch' => '[
                    { 
                        "method":"GET",
                        "name":"get-home",
                        "relative_url":"'.$url.'", 
                        "omit_response_on_success":false
                    },
                    {
                        "method":"GET",
                        "relative_url":"?ids={result=get-home:$.data.*.from.id}&fields=picture"
                    }
                    ]'
            )
        );
        $response = $request->execute();
        $graphObject = $response->getGraphObject();

        return $graphObject->asArray();
    }

    public function publishStatut($message)
    {
        $request = new FacebookRequest(
        $this->session->get('facebook')['session'],
          'POST',
          '/me/feed',
          array (
            'message' => $message,
          )
        );

        try {
            $response = $request->execute();
        } catch(FacebookRequestException $e) {
            return array(
                'code' => $e->getCode(),
                'message' => $e->getMessage()
            );
        }
        
        $graphObject = $response->getGraphObject();

        return $graphObject->asArray();
    }
}