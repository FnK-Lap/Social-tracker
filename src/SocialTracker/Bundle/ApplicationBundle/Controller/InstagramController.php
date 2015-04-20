<?php

namespace SocialTracker\Bundle\ApplicationBundle\Controller;

use SocialTracker\Bundle\ApplicationBundle\Entity\InstagramPost;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class InstagramController extends Controller
{
    public function homeAction()
    {
        $user = $this->get('security.context')->getToken()->getUser();

        if ($user->getInstagramAccessToken() === null) 
        {
            return $this->render('SocialTrackerApplicationBundle:Instagram:home.html.twig', array());
        }

        $em = $this->getDoctrine()->getManager();

        $userFeed = $em->getRepository('SocialTrackerApplicationBundle:InstagramPost')->findFeedByUser($user);

        foreach ($userFeed as &$feed) {
            $feed['content'] = json_decode($feed['content']);
        }

        return $this->render('SocialTrackerApplicationBundle:Instagram:home.html.twig', array(
            'userFeed' => $userFeed
        ));
    }

    public function refreshMediaAction(InstagramPost $media)
    {
        $instagram = $this->get('instagram');
        $user = $this->get('security.context')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();

        $newMedia = $instagram->refreshMedia($user->getInstagramAccessToken(), $media);

        return new Response(json_encode($newMedia));

    }

    public function deleteMediaAction(Request $request, $id)
    {
        if ($request->isXmlHttpRequest()) {
            $em = $this->getDoctrine()->getManager();
            $media = $em->getRepository('SocialTrackerApplicationBundle:InstagramPost')->find($id);
            if ($media->getUser() === $this->getUser()) {
                $em->remove($media);
                $em->flush();

                return new JsonResponse(array(
                    'status'  => 200,
                    'message' => 'OK'
                ));
            }
            throw new AccessDeniedException("Your are not the owner of this post");
        }

        throw new AccessDeniedException("Only XmlHttpRequest was authorized");
    }

    public function ajaxLikeMediaAction(InstagramPost $media)
    {
        $user = $this->get('security.context')->getToken()->getUser();
        $instagram = $this->get('instagram');

        $result = $instagram->likeMedia($user->getInstagramAccessToken(), $media->getInstagramId());
        $newMedia = $instagram->refreshMedia($user->getInstagramAccessToken(), $media);

        return new Response(json_encode($newMedia));
    }

    public function ajaxDislikeMediaAction(InstagramPost $media)
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
        $instagram = $this->get('instagram');
        $user = $this->get('security.context')->getToken()->getUser();

        $media = $em->getRepository('SocialTrackerApplicationBundle:InstagramPost')->findOneBy(array('instagram_id' => $id));
        $newMedia = $instagram->refreshMedia($user->getInstagramAccessToken(), $media);

        $newMedia->setContent(json_decode($newMedia->getContent()));

        return $this->render('SocialTrackerApplicationBundle:Instagram:showMedia.html.twig', array(
            'media' => $newMedia
        ));
    }
}