<?php

namespace SocialTracker\Bundle\ApplicationBundle\Controller;

use igorw;
use SocialTracker\Bundle\ApplicationBundle\Entity\FacebookPost;
use SocialTracker\Bundle\ApplicationBundle\Entity\InstagramPost;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use SocialTracker\Bundle\ApplicationBundle\Youtube\TokenResponse as YoutubeTokenResponse;


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
        $youtubeUrl     =   $this->get('youtube.authentication_helper')->getAuthorizeUrl();
        $user           =   $this->get('security.context')->getToken()->getUser();

        $em = $this->getDoctrine()->getManager();
        $userSettings = $em->getRepository('SocialTrackerApplicationBundle:User')->findSettingsByUser($user->getId());

        return $this->render('SocialTrackerApplicationBundle:Application:settings.html.twig', array(
            'userSettings' => $userSettings,
            'instagramUrl' => $instagramUrl,
            'facebookUrl'  => (isset($facebookUrl)) ? $facebookUrl : null,
            'youtubeUrl'   => $youtubeUrl
        ));
    }

    public function instagramCallbackAction(Request $request)
    {
        $em         = $this->getDoctrine()->getManager();
        $code       = $request->query->get('code');
        $helper     = $this->get('instagram.authentication_helper');
        $instagram  = $this->get('instagram');
        $response   = $helper->exchangeAuthorizationCode($code);

        // Store access token in DB
        $user = $this->get('security.context')->getToken()->getUser();
        $user->setInstagramAccessToken($response->accessToken);
        $user->setInstagramUsername($response->username);

        // Run worker
        $feed = $instagram->getUserFeed($response->accessToken, null, null, $user->getInstagramMaxId());
        // Sort array by creatd_time
        $tmp = array();
        foreach($feed['data'] as &$ma) {
            $tmp[] = &$ma["created_time"];
        }

        array_multisort($tmp, $feed['data']);

        if (count($feed['data']) > 0) {
            $user->setInstagramMaxId(igorw\get_in($feed, ['data', count($feed['data'])-1, 'id']));
            $em->persist($user);

            foreach ($feed['data'] as $post) {
                $instagramPost = new InstagramPost();
                $instagramPost->setInstagramId($post['id'])
                              ->setUser($user)
                              ->setCreatedTime($post['created_time'])
                              ->setContent(json_encode($post));
                $em->persist($instagramPost);
            }
        }

        $em->persist($user);
        $em->flush();

        return $this->redirect($this->generateUrl('application_settings'));
    }

    public function facebookCallbackAction(Request $request)
    {
        $em         = $this->getDoctrine()->getManager();
        $helper     = $this->get('facebook.authentication_helper');
        $facebook   = $this->get('facebook');
        $response   = $helper->exchangeAuthorizationCode();

        $user = $this->get('security.context')->getToken()->getUser();
        $user->setFacebookAccessToken($response->accessToken);
        $user->setFacebookUsername($response->username);

        // Run worker
        $facebookSession = $helper->newSessionFromAccessToken($response->accessToken);
        // $feed = $facebook->getUserFeed($facebookSession, array('since' => $user->getFacebookLastPost()));
        /*
        if ($feed) {
            $user->setFacebookLastPost(strtotime($feed[0]->created_time));
            $em->persist($user);

            foreach ($feed as $post) {
                $facebookPost = new FacebookPost();
                $facebookPost->setFacebookId($post->id)
                             ->setUser($user)
                             ->setCreatedTime(strtotime($post->created_time))
                             ->setContent(json_encode($post));
                $em->persist($facebookPost);
            }
        }
        */

        $em->persist($user);
        $em->flush();

        return $this->redirect($this->generateUrl('application_settings'));
    }

    public function youtubeCallbackAction(Request $request)
    {
        $helper       = $this->get('youtube.authentication_helper');
        $code         = $request->query->get('code');
        $response     = $helper->exchangeAuthorizationCode($code);

        if ($response instanceof YoutubeTokenResponse) {
            $user = $this->getUser()
                         ->setYoutubeAccessToken($response->accessToken)
                         ->setYoutubeUsername($response->username)
            ;

            if ($response->refreshToken) {
                $user->setYoutubeRefreshToken($response->refreshToken);
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
        } else {
            $this->get('session')->getFlashBag()->add(
                'error',
                $response['code'].' : '.$response['message']
            );
        }


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

        return new JsonResponse(array('code' => 200));
    }
}
