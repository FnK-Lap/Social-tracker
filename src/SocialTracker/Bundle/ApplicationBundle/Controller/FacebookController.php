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
        $facebookService = $this->get('facebook_service');

        if ($this->get('session')->get('facebook') === null) 
        {
            return $this->render('SocialTrackerApplicationBundle:Facebook:home.html.twig', array());
        }
        
        $userFeed = $facebookService->getUserFeed(array('limit' => 1));

        $feeds      = json_decode($userFeed[0]->body);
        $userCovers = json_decode($userFeed[1]->body); 

        $response = new Response();
        $response->setEtag(md5(json_encode($feeds->data[0]->id)));

        if ($response->isNotModified($this->getRequest())) {
            return $response;
        } else {
            $userFeed = $facebookService->getUserFeed();

            $feeds      = json_decode($userFeed[0]->body);
            $userCovers = json_decode($userFeed[1]->body); 

            return $this->render('SocialTrackerApplicationBundle:Facebook:home.html.twig', array(
                'feeds'       => $feeds,
                'userCovers'  => $userCovers
            ), $response);
        }
    }

    public function ajaxPublishAction(Request $request)
    {
        $message = $request->request->get('message');

        if (empty($message)) 
        {
            return new JsonResponse(array('code' => 409, 'message' => 'Le statut ne peux etre vide'));
        }

        $facebookService = $this->get('facebook_service');
        $result = $facebookService->publishStatut($message);

        return new JsonResponse($result);
    }

}








