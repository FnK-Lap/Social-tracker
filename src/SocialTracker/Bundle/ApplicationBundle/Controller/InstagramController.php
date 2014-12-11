<?php

namespace SocialTracker\Bundle\ApplicationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class InstagramController extends Controller
{
    public function homeAction()
    {
        $instagramService = $this->get('instagram_service');

        if ($this->get('session')->get('instagram') === null) 
        {
            return $this->render('SocialTrackerApplicationBundle:Instagram:home.html.twig', array());
        }

        $userFeed = $instagramService->getUserFeed(1);

        $response = new Response();
        $response->setEtag(md5(json_encode($userFeed['data'][0]['images'])));

        if ($response->isNotModified($this->getRequest())) {
            // Retourne immédiatement un objet 304 Response
            return $response;
        } else {
            // faire plus de travail ici - comme récupérer plus de données
            $userFeed = $instagramService->getUserFeed();

            // ou formatter un template avec la $response déjà existante
            return $this->render('SocialTrackerApplicationBundle:Instagram:home.html.twig', array(
                'userFeed' => $userFeed
            ), $response);
        }

    }

    public function ajaxUserFeedAction($maxId)
    {
        $instagramService = $this->get('instagram_service');
        $userFeed = $instagramService->getUserFeed(null, $maxId);

        return new Response(json_encode($userFeed));
    }

    public function showMediaAction($id)
    {
        $instagramService = $this->get('instagram_service');
        $media = $instagramService->getMedia($id);

        return $this->render('SocialTrackerApplicationBundle:Instagram:showMedia.html.twig', array(
            'media' => $media
        ));
    }

    public function ajaxLikeMediaAction($id)
    {
        $instagramService = $this->get('instagram_service');

        $result = $instagramService->likeMedia($id);

        return new Response(json_encode($result));
    }

    public function ajaxDislikeMediaAction($id)
    {
        $instagramService = $this->get('instagram_service');

        $result = $instagramService->dislikeMedia($id);

        return new Response(json_encode($result));
    }
}