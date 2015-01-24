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
        $facebookUrl    =   $this->get('facebook.authentication_helper')->getAuthorizeUrl();
        $user           =   $this->get('security.context')->getToken()->getUser();

        $em = $this->getDoctrine()->getManager();
        $userSettings = $em->getRepository('SocialTrackerApplicationBundle:User')->findSettingsByUser($user->getId());

        return $this->render('SocialTrackerApplicationBundle:Application:settings.html.twig', array(
            'userSettings'  => $userSettings,
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

    public function facebookCallbackAction(Request $request)
    {
        $em         = $this->getDoctrine()->getManager();
        $helper     = $this->get('facebook.authentication_helper');
        $response   = $helper->exchangeAuthorizationCode();

        $user = $this->get('security.context')->getToken()->getUser();
        $user->setFacebookAccessToken($response->accessToken);
        $user->setFacebookUsername($response->username);

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
