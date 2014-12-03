<?php

namespace SocialTracker\Bundle\ApplicationBundle\Services;

class ApplicationService
{
    private $em;
    private $securityContext;

    public function __construct($em, $securityContext)
    {
        $this->em = $em;
        $this->securityContext = $securityContext;
    }

    public function addSocial($social)
    {
        $user = $this->securityContext->getToken()->getUser();
        $userSocial = $user->getSocial();

        if (in_array($social, $userSocial)) {
            return array(
                'code' => 409,
                'message' => 'Ce réseau social est déjà activé'
            );
            
        }

        array_push($userSocial, $social);

        $user->setSocial($userSocial);
        $this->em->persist($user);
        $this->em->flush();

        return array(
            'code' => 201,
            'message' => 'Le réseau social '.ucfirst($social).' à été activé'
        );
    }

    public function removeSocial($social)
    {
        $user = $this->securityContext->getToken()->getUser();
        $userSocial = $user->getSocial();

        if (!in_array($social, $userSocial)) {
            return array(
                'code' => 409,
                'message' => 'Ce réseau social est déjà désactivé'
            );
            
        }

        $key = array_keys($userSocial, $social)[0];
        unset($userSocial[$key]);

        $user->setSocial($userSocial);
        $this->em->persist($user);
        $this->em->flush();

        return array(
            'code' => 201,
            'message' => 'Le réseau social '.ucfirst($social).' à été activé'
        );
    }

    public function getSocials()
    {
        $user = $this->securityContext->getToken()->getUser();
        $userSocial = $user->getSocial();

        return $userSocial;
    }

}