<?php

namespace SocialTracker\Bundle\ApplicationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use FOS\UserBundle\Model\User as BaseUser;

/**
 * InstagramPost
 */
class InstagramPost
{
    
    /**
     * @var integer
     */
    private $id;


    /**
     * Set id
     *
     * @param integer $id
     * @return InstagramPost
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
    /**
     * @var string
     */
    private $content;


    /**
     * Set content
     *
     * @param string $content
     * @return InstagramPost
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string 
     */
    public function getContent()
    {
        return $this->content;
    }
    /**
     * @var integer
     */
    private $created_time;


    /**
     * Set createdTime
     *
     * @param integer $createdTime
     * @return InstagramPost
     */
    public function setCreatedTime($createdTime)
    {
        $this->created_time = $createdTime;

        return $this;
    }

    /**
     * Get createdTime
     *
     * @return integer 
     */
    public function getCreatedTime()
    {
        return $this->created_time;
    }
    /**
     * @var string
     */
    private $instagram_id;


    /**
     * Set instagram_id
     *
     * @param string $instagramId
     * @return InstagramPost
     */
    public function setInstagramId($instagramId)
    {
        $this->instagram_id = $instagramId;

        return $this;
    }

    /**
     * Get instagram_id
     *
     * @return string 
     */
    public function getInstagramId()
    {
        return $this->instagram_id;
    }
    /**
     * @var \SocialTracker\Bundle\ApplicationBundle\Entity\User
     */
    private $user;


    /**
     * Set user
     *
     * @param \SocialTracker\Bundle\ApplicationBundle\Entity\User $user
     * @return InstagramPost
     */
    public function setUser(\SocialTracker\Bundle\ApplicationBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \SocialTracker\Bundle\ApplicationBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }
}
