<?php

namespace SocialTracker\Bundle\ApplicationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class ApplicationController extends Controller
{
    public function homeAction()
    {
        return $this->render('SocialTrackerApplicationBundle:Application:home.html.twig', array(
            
        ));
    }

    public function settingsAction(Request $request)
    {
        $applicationService = $this->get('application_service');
        $instagramService   = $this->get('instagram_service');
        $facebookService    = $this->get('facebook_service');

        $activeSocials = $applicationService->getSocials();

        $instagramUrl = $instagramService->getAuthorizeUrl();

        $code   = $request->query->get('code');
        $social = $request->query->get('social');

        if ($social != 'facebook') 
        {
            $facebookUrl  = $facebookService->getAuthorizeUrl();
        }

        if (!empty($code))
        {
            if ($social == 'instagram') 
            {
                $instagramService->getAccessToken($code);
            }
            elseif ($social == 'facebook') 
            {
                $result = $facebookService->getAccessToken();
                if ($result['code'] != 200) 
                {
                    $session = $this->get('session');
                    $session->getFlashBag()->add(
                        'error',
                        $result['message']
                    );
                }
            }
            
        }

        return $this->render('SocialTrackerApplicationBundle:Application:settings.html.twig', array(
            'activeSocials' => $activeSocials,
            'instagramUrl' => $instagramUrl,
            'facebookUrl'  => (isset($facebookUrl)) ? $facebookUrl : null
        ));
    }

    public function ajaxAddSocialAction(Request $request)
    {
        $social = $request->request->get('social');

        $applicationService = $this->get('application_service');
        $response = $applicationService->addSocial($social);

        if ($response['code'] == 409) 
        {
            $this->get('session')->getFlashBag()->add(
                'error',
                $response['message']
            );
        }
        else
        {
            $this->get('session')->getFlashBag()->add(
                'success',
                $response['message']
            );
        }
        
        return new Response(json_encode($response), $response['code']);
    }

    public function ajaxRemoveSocialAction(Request $request)
    {
        $social = $request->request->get('social');

        $session = $this->get('session');

        $applicationService = $this->get('application_service');
        $response = $applicationService->removeSocial($social);

        if ($response['code'] == 409) 
        {
            $session->getFlashBag()->add(
                'error',
                $response['message']
            );
        }
        else
        {
            if ($session->has($social))
            {
                $session->remove($social);
            }

            $session->getFlashBag()->add(
                'success',
                $response['message']
            );
        }

        return new Response(json_encode($response), $response['code']);
    }
}
