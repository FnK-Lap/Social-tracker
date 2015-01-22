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
                       ->select('users.instagram_access_token, users.instagram_username')
                       ->getQuery()
                       ->getArrayResult();

        $settings['instagram'] = array(
            'access_token' => $result[0]['instagram_access_token'],
            'username'     => $result[0]['instagram_username']
        ); 

        return $settings;
    }
}