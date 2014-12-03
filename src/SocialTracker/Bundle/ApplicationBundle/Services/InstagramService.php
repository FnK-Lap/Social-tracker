<?php

namespace SocialTracker\Bundle\ApplicationBundle\Services;

class InstagramService
{
    private $clientId;
    private $clientSecret;
    private $redirectUri;
    private $client;
    private $session;

    public function __construct($clientId, $clientSecret, $client, $session)
    {
        $this->clientId     = $clientId;
        $this->clientSecret = $clientSecret;
        $this->redirectUri  = 'http://social-tracker.dev/settings?social=instagram';
        $this->client   = $client;
        $this->session  = $session;
    }

    public function getAuthorizeUrl()
    {
        $authorizeUri = "https://api.instagram.com/oauth/authorize/?client_id=".$this->clientId."&redirect_uri=".$this->redirectUri."&response_type=code&scope=likes+comments";

        return $authorizeUri;
    }

    public function getAccessToken($code)
    {
        $data = array(
            'client_id'      => $this->clientId,
            'client_secret'  => $this->clientSecret,
            'grant_type'     => 'authorization_code',
            'redirect_uri'   => $this->redirectUri,
            'code'           => $code
        );

        $request = $this->client->post('https://api.instagram.com/oauth/access_token', null, $data);

        $response = $request->send()->json();


        $userData = array(
            'access_token'  => $response['access_token'],
            'id'            => $response['user']['id'],
            'username'      => $response['user']['username']
        );

        $this->session->set('instagram', $userData);
    }

    public function getUserFeed($count = null, $maxId = null)
    {
        $userData = $this->session->get('instagram');
        $uri = 'https://api.instagram.com/v1/users/self/feed?access_token='.$userData['access_token'];

        if (isset($count)) 
        {
            $uri .= '&count='.intval($count);
        }
        if (isset($maxId)) 
        {
            $uri .= '&max_id='.$maxId;
        }

        $request = $this->client->get($uri);

        $response = $request->send()->json();

        return $response;
    }

    public function getMedia($id)
    {
        $userData = $this->session->get('instagram');
        $uri = "https://api.instagram.com/v1/media/" . $id . "?access_token=" . $userData['access_token'];

        $request = $this->client->get($uri);
        $response = $request->send()->json();

        return $response;
    }

    public function likeMedia($id)
    {
        $userData = $this->session->get('instagram');
        $data = array(
            'access_token' => $userData['access_token']
        );

        $request = $this->client->post("https://api.instagram.com/v1/media/" . $id . "/likes", null, $data);
        $response = $request->send()->json();

        return $response;
    }

    public function dislikeMedia($id)
    {
        $userData = $this->session->get('instagram');

        $request = $this->client->delete("https://api.instagram.com/v1/media/" . $id . "/likes?access_token=".$userData['access_token']);
        $response = $request->send()->json();

        return $response;
    }

}