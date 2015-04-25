<?php

namespace SocialTracker\Bundle\ApplicationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class YoutubeController extends Controller
{
    public function homeAction()
    {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $this->get('youtube');

        if ($user->getYoutubeAccessToken() === null) {
            return $this->render('SocialTrackerApplicationBundle:Youtube:home.html.twig');
        }

        // $userFeed = $em->getRepository('SocialTrackerApplicationBundle:YoutubePost')->findFeedByUser($user);

        return $this->render('SocialTrackerApplicationBundle:Youtube:home.html.twig', array(

        ));
    }
}








