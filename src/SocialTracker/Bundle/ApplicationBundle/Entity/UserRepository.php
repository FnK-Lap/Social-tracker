<?php

namespace SocialTracker\Bundle\ApplicationBundle\Entity;

use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository
{
    public function findSettingsByUser($userId)
    {
        $result = $this->createQueryBuilder('users')
                       ->where('users.id = :id')
                       ->setParameter('id', $userId)
                       ->select('users.instagram_access_token, users.instagram_username, users.facebook_access_token, users.facebook_username, users.youtube_access_token, users.youtube_username')
                       ->getQuery()
                       ->getArrayResult();

        $settings['instagram'] = array(
            'access_token' => $result[0]['instagram_access_token'],
            'username'     => $result[0]['instagram_username']
        );

        $settings['facebook'] = array(
            'access_token' => $result[0]['facebook_access_token'],
            'username'     => $result[0]['facebook_username']
        ); 

        $settings['youtube'] = array(
            'access_token' => $result[0]['youtube_access_token'],
            'username'     => $result[0]['youtube_username']
        ); 

        return $settings;
    }
}