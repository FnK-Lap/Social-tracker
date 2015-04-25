<?php

namespace SocialTracker\Bundle\ApplicationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * FacebookPost
 */
class FacebookPost
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $content;

    /**
     * @var string
     */
    private $facebookId;

    /**
     * @var string
     */
    private $createdTime;


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
     * Set content
     *
     * @param string $content
     * @return FacebookPost
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
     * Set facebookId
     *
     * @param string $facebookId
     * @return FacebookPost
     */
    public function setFacebookId($facebookId)
    {
        $this->facebookId = $facebookId;

        return $this;
    }

    /**
     * Get facebookId
     *
     * @return string 
     */
    public function getFacebookId()
    {
        return $this->facebookId;
    }

    /**
     * Set createdTime
     *
     * @param string $createdTime
     * @return FacebookPost
     */
    public function setCreatedTime($createdTime)
    {
        $this->createdTime = $createdTime;

        return $this;
    }

    /**
     * Get createdTime
     *
     * @return string 
     */
    public function getCreatedTime()
    {
        return $this->createdTime;
    }
    /**
     * @var \SocialTracker\Bundle\ApplicationBundle\Entity\User
     */
    private $user;


    /**
     * Set user
     *
     * @param \SocialTracker\Bundle\ApplicationBundle\Entity\User $user
     * @return FacebookPost
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
