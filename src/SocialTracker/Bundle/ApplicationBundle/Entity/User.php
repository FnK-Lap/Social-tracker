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
    /**
     * @var string
     */
    private $facebook_session;

    /**
     * @var string
     */
    private $facebook_username;


    /**
     * Set facebook_session
     *
     * @param string $facebookSession
     * @return User
     */
    public function setFacebookSession($facebookSession)
    {
        $this->facebook_session = $facebookSession;

        return $this;
    }

    /**
     * Get facebook_session
     *
     * @return string 
     */
    public function getFacebookSession()
    {
        return $this->facebook_session;
    }

    /**
     * Set facebook_username
     *
     * @param string $facebookUsername
     * @return User
     */
    public function setFacebookUsername($facebookUsername)
    {
        $this->facebook_username = $facebookUsername;

        return $this;
    }

    /**
     * Get facebook_username
     *
     * @return string 
     */
    public function getFacebookUsername()
    {
        return $this->facebook_username;
    }
    /**
     * @var string
     */
    private $facebook_access_token;


    /**
     * Set facebook_access_token
     *
     * @param string $facebookAccessToken
     * @return User
     */
    public function setFacebookAccessToken($facebookAccessToken)
    {
        $this->facebook_access_token = $facebookAccessToken;

        return $this;
    }

    /**
     * Get facebook_access_token
     *
     * @return string 
     */
    public function getFacebookAccessToken()
    {
        return $this->facebook_access_token;
    }

    /**
     * @var integer
     */
    private $facebook_last_post;


    /**
     * Set facebook_last_post
     *
     * @param integer $facebookLastPost
     * @return User
     */
    public function setFacebookLastPost($facebookLastPost)
    {
        $this->facebook_last_post = $facebookLastPost;

        return $this;
    }

    /**
     * Get facebook_last_post
     *
     * @return integer 
     */
    public function getFacebookLastPost()
    {
        return $this->facebook_last_post;
    }
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $facebook_posts;


    /**
     * Add facebook_posts
     *
     * @param \SocialTracker\Bundle\ApplicationBundle\Entity\FacebookPost $facebookPosts
     * @return User
     */
    public function addFacebookPost(\SocialTracker\Bundle\ApplicationBundle\Entity\FacebookPost $facebookPosts)
    {
        $this->facebook_posts[] = $facebookPosts;

        return $this;
    }

    /**
     * Remove facebook_posts
     *
     * @param \SocialTracker\Bundle\ApplicationBundle\Entity\FacebookPost $facebookPosts
     */
    public function removeFacebookPost(\SocialTracker\Bundle\ApplicationBundle\Entity\FacebookPost $facebookPosts)
    {
        $this->facebook_posts->removeElement($facebookPosts);
    }

    /**
     * Get facebook_posts
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getFacebookPosts()
    {
        return $this->facebook_posts;
    }
}
