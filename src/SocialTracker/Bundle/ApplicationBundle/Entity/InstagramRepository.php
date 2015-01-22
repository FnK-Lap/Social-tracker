<?php

namespace SocialTracker\Bundle\ApplicationBundle\Entity;

use Doctrine\ORM\EntityRepository;

class InstagramRepository extends EntityRepository
{
    public function findFeedByUser(User $user)
    {
        return $this->createQueryBuilder('instagram')
                    ->where('instagram.user = :id')
                    ->setParameter('id', $user->getId())
                    ->orderBy('instagram.created_time', 'DESC')
                    ->getQuery()
                    ->getArrayResult();
    }
}