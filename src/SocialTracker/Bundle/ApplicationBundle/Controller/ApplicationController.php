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
        $instagramUrl   =   $this->get('instagram.authentication_helper')->getAuthorizeUrl();
        $application    =   $this->get('application');
        $user           =   $this->get('security.context')->getToken()->getUser();

        $em = $this->getDoctrine()->getManager();
        $userSettings = $em->getRepository('SocialTrackerApplicationBundle:User')->findSettingsByUser($user->getId());


        // $instagramService   = $this->get('instagram_service');
        // $facebookService    = $this->get('facebook_service');


        // $instagramUrl = $instagramService->getAuthorizeUrl();

        // $code   = $request->query->get('code');
        // $social = $request->query->get('social');

        // if ($social != 'facebook') 
        // {
            // $facebookUrl  = $facebookService->getAuthorizeUrl();
        // }

        // if (!empty($code))
        // {
            // if ($social == 'instagram') 
            // {
                // $instagramService->getAccessToken($code);
            // }
            // elseif ($social == 'facebook') 
            // {
                // $result = $facebookService->getAccessToken();
                // if ($result['code'] != 200) 
                // {
                    // $session = $this->get('session');
                    // $session->getFlashBag()->add(
                        // 'error',
                        // $result['message']
                    // );
                // }
            // }   
        // }

        return $this->render('SocialTrackerApplicationBundle:Application:settings.html.twig', array(
            'userSettings'  => $userSettings,
            // 'activeSocials' => $activeSocials,
            'instagramUrl' => $instagramUrl,
            'facebookUrl'  => (isset($facebookUrl)) ? $facebookUrl : null
        ));
    }

    public function instagramCallbackAction(Request $request)
    {
        $em       = $this->getDoctrine()->getManager();
        $code     = $request->query->get('code');
        $helper   = $this->get('instagram.authentication_helper');
        $response = $helper->exchangeAuthorizationCode($code);

        // Store access token in DB
        $user = $this->get('security.context')->getToken()->getUser();
        $user->setInstagramAccessToken($response->accessToken);
        $user->setInstagramUsername($response->username);
        $em->persist($user);
        $em->flush();

        return $this->redirect($this->generateUrl('application_settings'));
    }

    public function disableSocialAction($social)
    {
        $user = $this->get('security.context')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();

        $setterAccessToken = "set" . ucfirst($social) . "AccessToken";
        $setterUsername = "set" . ucfirst($social) . "Username";
        $user->$setterAccessToken(null);
        $user->$setterUsername(null);

        $em->persist($user);
        $em->flush();
    }
}
