<?php

namespace SocialTracker\Bundle\ApplicationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class FacebookController extends Controller
{
    public function homeAction()
    {
        $facebook = $this->get('facebook');
        $user = $this->get('security.context')->getToken()->getUser();
        
        if ($user->getFacebookAccessToken() === null) 
        {
            return $this->render('SocialTrackerApplicationBundle:Facebook:home.html.twig', array());
        }

        $em = $this->getDoctrine()->getManager();

        $userFeed = $em->getRepository('SocialTrackerApplicationBundle:FacebookPost')->findFeedByUser($user);

        foreach ($userFeed as &$feed) {
            $feed['content'] = json_decode($feed['content']);
        }

        return $this->render('SocialTrackerApplicationBundle:Facebook:home.html.twig', array(
            'userFeed' => $userFeed,
        ));
    }

    public function ajaxPublishAction(Request $request)
    {
        $user = $this->get('security.context')->getToken()->getUser();
        $message = $request->request->get('message');
        if (empty($message)) {
            return new JsonResponse(array('code' => 409, 'message' => 'Le statut ne peux etre vide'));
        }

        $facebook = $this->get('facebook');
        $facebook->publish($user->getFacebookAccessToken(), $message);

        return new JsonResponse(array('code' => 201));
    }
}








