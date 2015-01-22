<?php

namespace SocialTracker\Bundle\ApplicationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use FOS\UserBundle\Model\User as BaseUser;

/**
 * User
 */
class User extends BaseUser
{
    /**
     * @var integer
     */
    protected $id;

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
    private $instagram_access_token;


    /**
     * Set instagram_access_token
     *
     * @param string $instagramAccessToken
     * @return User
     */
    public function setInstagramAccessToken($instagramAccessToken)
    {
        $this->instagram_access_token = $instagramAccessToken;

        return $this;
    }

    /**
     * Get instagram_access_token
     *
     * @return string 
     */
    public function getInstagramAccessToken()
    {
        return $this->instagram_access_token;
    }
    /**
     * @var string
     */
    private $instagram_max_id;


    /**
     * Set instagram_max_id
     *
     * @param string $instagramMaxId
     * @return User
     */
    public function setInstagramMaxId($instagramMaxId)
    {
        $this->instagram_max_id = $instagramMaxId;

        return $this;
    }

    /**
     * Get instagram_max_id
     *
     * @return string 
     */
    public function getInstagramMaxId()
    {
        return $this->instagram_max_id;
    }
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $instagram_posts;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->instagram_posts = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add instagram_posts
     *
     * @param \SocialTracker\Bundle\ApplicationBundle\Entity\Instagram $instagramPosts
     * @return User
     */
    public function addInstagramPost(\SocialTracker\Bundle\ApplicationBundle\Entity\Instagram $instagramPosts)
    {
        $this->instagram_posts[] = $instagramPosts;

        return $this;
    }

    /**
     * Remove instagram_posts
     *
     * @param \SocialTracker\Bundle\ApplicationBundle\Entity\Instagram $instagramPosts
     */
    public function removeInstagramPost(\SocialTracker\Bundle\ApplicationBundle\Entity\Instagram $instagramPosts)
    {
        $this->instagram_posts->removeElement($instagramPosts);
    }

    /**
     * Get instagram_posts
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getInstagramPosts()
    {
        return $this->instagram_posts;
    }
    /**
     * @var string
     */
    private $instagram_username;


    /**
     * Set instagram_username
     *
     * @param string $instagramUsername
     * @return User
     */
    public function setInstagramUsername($instagramUsername)
    {
        $this->instagram_username = $instagramUsername;

        return $this;
    }

    /**
     * Get instagram_username
     *
     * @return string 
     */
    public function getInstagramUsername()
    {
        return $this->instagram_username;
    }
}
