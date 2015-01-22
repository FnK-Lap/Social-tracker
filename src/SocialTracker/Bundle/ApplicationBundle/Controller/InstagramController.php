<?php

namespace SocialTracker\Bundle\ApplicationBundle\Controller;

use SocialTracker\Bundle\ApplicationBundle\Entity\Instagram;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class InstagramController extends Controller
{
    public function homeAction()
    {
        $instagram = $this->get('instagram');
        $user = $this->get('security.context')->getToken()->getUser();

        if ($user->getInstagramAccessToken() === null) 
        {
            return $this->render('SocialTrackerApplicationBundle:Instagram:home.html.twig', array());
        }

        $em = $this->getDoctrine()->getManager();

        $userFeed = $em->getRepository('SocialTrackerApplicationBundle:Instagram')->findFeedByUser($user);

        foreach ($userFeed as &$feed) {
            $feed['content'] = json_decode($feed['content']);
        }

        return $this->render('SocialTrackerApplicationBundle:Instagram:home.html.twig', array(
            'userFeed' => $userFeed
        ));
    }

    public function refreshMediaAction(Instagram $media)
    {
        $instagram = $this->get('instagram');
        $user = $this->get('security.context')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();

        $newMedia = $instagram->refreshMedia($user->getInstagramAccessToken(), $media);

        return new Response(json_encode($newMedia));

    }

    public function ajaxLikeMediaAction(Instagram $media)
    {
        $user = $this->get('security.context')->getToken()->getUser();
        $instagram = $this->get('instagram');

        $result = $instagram->likeMedia($user->getInstagramAccessToken(), $media->getInstagramId());
        $newMedia = $instagram->refreshMedia($user->getInstagramAccessToken(), $media);

        return new Response(json_encode($newMedia));
    }

    public function ajaxDislikeMediaAction(Instagram $media)
    {
        $user = $this->get('security.context')->getToken()->getUser();
        $instagram = $this->get('instagram');

        $result = $instagram->dislikeMedia($user->getInstagramAccessToken(), $media->getInstagramId());
        $newMedia = $instagram->refreshMedia($user->getInstagramAccessToken(), $media);

        return new Response(json_encode($newMedia));
    }

    public function showMediaAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $media = $em->getRepository('SocialTrackerApplicationBundle:Instagram')->findOneBy(array('instagram_id' => $id));
        $media->setContent(json_decode($media->getContent()));

        return $this->render('SocialTrackerApplicationBundle:Instagram:showMedia.html.twig', array(
            'media' => $media
        ));
    }





    public function ajaxUserFeedAction($maxId)
    {
        $instagramService = $this->get('instagram_service');
        $userFeed = $instagramService->getUserFeed(null, $maxId);

        return new Response(json_encode($userFeed));
    }
}