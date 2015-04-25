<?php

namespace SocialTracker\Bundle\ApplicationBundle\Entity;

use Doctrine\ORM\EntityRepository;

class InstagramPostRepository extends EntityRepository
{
    public function findFeedByUser(User $user)
    {
        return $this->createQueryBuilder('instagramPosts')
                    ->where('instagramPosts.user = :id')
                    ->setParameter('id', $user->getId())
                    ->orderBy('instagramPosts.created_time', 'DESC')
                    ->getQuery()
                    ->getArrayResult();
    }
}