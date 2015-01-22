<?php

namespace SocialTracker\Bundle\ApplicationBundle\Instagram;

use Guzzle\Service\Client;
use SocialTracker\Bundle\ApplicationBundle\Entity\User;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\Router;

class Instagram
{
    private $guzzleClient;
    private $em;

    public function __construct(Client $guzzleClient, $em)
    {
        $this->guzzleClient = $guzzleClient;
        $this->em = $em;
        
    }
  
    public function getUserFeed($accessToken, $count = null, $maxId = null, $minId = null, $prevData = null)
    {
        $query = array('access_token' => rawurlencode($accessToken));

        if ($count) {
            $query['count'] = $count;
        }
        if ($maxId) {
            $query['max_id'] = $maxId;
        }
        if ($minId) {
            $query['min_id'] = $minId;
        }

        $url = sprintf('https://api.instagram.com/v1/users/self/feed?%s', http_build_query($query));

        $request = $this->guzzleClient->get($url);

        $data = $request->send()->json();

        if ($prevData) {
            foreach ($prevData['data'] as $post) {
                $data['data'][] = $post;
            }
            array_merge($data['data'], $prevData['data']);
        }

        if ($data['pagination']) {
            return $this->getUserFeed($accessToken, $count, $data['pagination']['next_max_id'], $minId, $data); 
        }else{
            return $data;
        }   
    }

    public function getMedia($accessToken, $mediaId)
    {
        $query = array('access_token' => rawurlencode($accessToken));

        $url = sprintf("https://api.instagram.com/v1/media/%s?%s", $mediaId, http_build_query($query));

        $request = $this->guzzleClient->get($url);
        $response = $request->send()->json();

        return $response;
    }

    public function refreshMedia($accessToken, $media)
    {   
        $refreshedMedia = $this->getMedia($accessToken, $media->getInstagramId());

        $media->setContent(json_encode($refreshedMedia['data']));

        $this->em->persist($media);
        $this->em->flush();

        return $media;
    }

    public function likeMedia($accessToken, $mediaId)
    {
        $accessToken = array('access_token' => $accessToken);

        $url = sprintf('https://api.instagram.com/v1/media/%s/likes', $mediaId);
        $request = $this->guzzleClient->post($url, null, $accessToken);
        
        return $request->send()->json();
    }

    public function dislikeMedia($accessToken, $mediaId)
    {
        $accessToken = array('access_token' => $accessToken);

        $url = sprintf('https://api.instagram.com/v1/media/%s/likes?%s', $mediaId, http_build_query($accessToken));
        $request = $this->guzzleClient->delete($url);
        
        return $request->send()->json();
    }
}