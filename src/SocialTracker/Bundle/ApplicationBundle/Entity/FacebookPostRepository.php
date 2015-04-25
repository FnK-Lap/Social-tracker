<?php

namespace SocialTracker\Bundle\ApplicationBundle\Entity;

use Doctrine\ORM\EntityRepository;

class FacebookPostRepository extends EntityRepository
{
    public function findFeedByUser(User $user)
    {
        return $this->createQueryBuilder('facebookPosts')
                    ->where('facebookPosts.user = :id')
                    ->setParameter('id', $user->getId())
                    ->orderBy('facebookPosts.createdTime', 'DESC')
                    ->getQuery()
                    ->getArrayResult();
    }
}
