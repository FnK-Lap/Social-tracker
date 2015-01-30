<?php

namespace SocialTracker\Bundle\ApplicationBundle\Facebook;

use Facebook\FacebookRequest;
use Facebook\FacebookSession;
use SocialTracker\Bundle\ApplicationBundle\Entity\User;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\Router;

class Facebook
{
    private $authenticationHelper;

    public function __construct($authenticationHelper)
    {
        $this->authenticationHelper = $authenticationHelper;
    }

    public function getUserFeed($session, $params = array(), $prevData = null)
    {
        $url = sprintf('me/home?%s', http_build_query($params));

        $request = new FacebookRequest(
            $session, 
            'POST', 
            '', 
            array(
                'access_token' => $session->getAccessToken(),
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

        $response = $request->execute()->getGraphObject()->asArray();
        $data = json_decode($response[0]->body)->data;
        $userPicture = json_decode($response[1]->body);

        // Add profile pictures for each posts
        foreach ($data as $value) {
            $id = $value->from->id;
            $picture = $userPicture->$id->picture->data->url;
            $value->from->url = $picture;
        }


        if ($data) {
            if ($prevData) {
                $data = array_merge($data, $prevData);
            }
            return $this->getUserFeed($session, array('since' => strtotime($data[0]->created_time)), $data);
        }
        return $prevData;
    }

    public function publish($accessToken, $message)
    {
        $session = $this->authenticationHelper->newSessionFromAccessToken($accessToken);

        $request = new FacebookRequest($session, 'POST', '/me/feed', array('message' => $message));

        return $request->execute()->getGraphObject()->asArray();
    }
}