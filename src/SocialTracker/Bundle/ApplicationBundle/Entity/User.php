<?php

namespace SocialTracker\Bundle\ApplicationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * User
 */
class User implements UserInterface, \Serializable
{
    /**
     * @var integer
     */
    protected $id;

    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $salt;

    /**
     * @var string
     */
    private $password;

    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $instagram_access_token;

    /**
     * @var string
     */
    private $instagram_max_id;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $instagram_posts;
    
    /**
     * @var string
     */
    private $instagram_username;
    
    /**
     * @var string
     */
    private $facebook_session;

    /**
     * @var string
     */
    private $facebook_username;
    
    /**
     * @var string
     */
    private $facebook_access_token;
    
    /**
     * @var integer
     */
    private $facebook_last_post;
    
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $facebook_posts;
    
    /**
     * @var string
     */
    private $youtube_access_token;
    
    /**
     * @var string
     */
    private $youtube_username;
    
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $youtube_posts;
    
    /**
     * @var string
     */
    private $youtube_refresh_token;
    
    /**
     * @var string
     */
    private $twitter_access_token;

    /**
     * @var string
     */
    private $googleAuthenticatorSecret;


    public function __construct()
    {
        $this->salt = md5(uniqid(null, true));
    }
    
    /**
     * Get username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set username
     *
     * @param string $username
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get salt
     *
     * @return string
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get roles
     *
     * @return array
     */
    public function getRoles()
    {
        return array('ROLE_USER');
    }

    /**
     * @inheritDoc
     */
    public function eraseCredentials()
    {
    }

    /**
     * @see \Serializable::serialize()
     */
    public function serialize()
    {
        return serialize(array(
            $this->id,
        ));
    }

    /**
     * @see \Serializable::unserialize()
     */
    public function unserialize($serialized)
    {
        list (
            $this->id,
        ) = unserialize($serialized);
    }

    /**
     * Get googleAuthenticatorSecret
     *
     * @return string
     */
    public function getGoogleAuthenticatorSecret() 
    {
        return $this->googleAuthenticatorSecret;
    }

    /**
     * Set googleAuthenticatorSecret
     *
     * @param string $googleAuthenticatorSecret
     * @return User
     */
    public function setGoogleAuthenticatorSecret($googleAuthenticatorSecret) 
    {
        $this->googleAuthenticatorSecret = $googleAuthenticatorSecret;
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
     * Get instagram_posts
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getInstagramPosts()
    {
        return $this->instagram_posts;
    }

    /**
     * Add instagram_posts
     *
     * @param \SocialTracker\Bundle\ApplicationBundle\Entity\InstagramPost $instagramPosts
     * @return User
     */
    public function addInstagramPost(\SocialTracker\Bundle\ApplicationBundle\Entity\InstagramPost $instagramPosts)
    {
        $this->instagram_posts[] = $instagramPosts;

        return $this;
    }

    /**
     * Remove instagram_posts
     *
     * @param \SocialTracker\Bundle\ApplicationBundle\Entity\InstagramPost $instagramPosts
     */
    public function removeInstagramPost(\SocialTracker\Bundle\ApplicationBundle\Entity\InstagramPost $instagramPosts)
    {
        $this->instagram_posts->removeElement($instagramPosts);
    }
    
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

    /**
     * Set youtube_access_token
     *
     * @param string $youtubeAccessToken
     * @return User
     */
    public function setYoutubeAccessToken($youtubeAccessToken)
    {
        $this->youtube_access_token = $youtubeAccessToken;

        return $this;
    }

    /**
     * Get youtube_access_token
     *
     * @return string 
     */
    public function getYoutubeAccessToken()
    {
        return $this->youtube_access_token;
    }

    /**
     * Set youtube_username
     *
     * @param string $youtubeUsername
     * @return User
     */
    public function setYoutubeUsername($youtubeUsername)
    {
        $this->youtube_username = $youtubeUsername;

        return $this;
    }

    /**
     * Get youtube_username
     *
     * @return string 
     */
    public function getYoutubeUsername()
    {
        return $this->youtube_username;
    }

    /**
     * Add youtube_posts
     *
     * @param \SocialTracker\Bundle\ApplicationBundle\Entity\YoutubePost $youtubePosts
     * @return User
     */
    public function addYoutubePost(\SocialTracker\Bundle\ApplicationBundle\Entity\YoutubePost $youtubePosts)
    {
        $this->youtube_posts[] = $youtubePosts;

        return $this;
    }

    /**
     * Remove youtube_posts
     *
     * @param \SocialTracker\Bundle\ApplicationBundle\Entity\YoutubePost $youtubePosts
     */
    public function removeYoutubePost(\SocialTracker\Bundle\ApplicationBundle\Entity\YoutubePost $youtubePosts)
    {
        $this->youtube_posts->removeElement($youtubePosts);
    }

    /**
     * Get youtube_posts
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getYoutubePosts()
    {
        return $this->youtube_posts;
    }

    /**
     * Set youtube_refresh_token
     *
     * @param string $youtubeRefreshToken
     * @return User
     */
    public function setYoutubeRefreshToken($youtubeRefreshToken)
    {
        $this->youtube_refresh_token = $youtubeRefreshToken;

        return $this;
    }

    /**
     * Get youtube_refresh_token
     *
     * @return string 
     */
    public function getYoutubeRefreshToken()
    {
        return $this->youtube_refresh_token;
    }

    /**
     * Set twitter_access_token
     *
     * @param string $twitterAccessToken
     * @return User
     */
    public function setTwitterAccessToken($twitterAccessToken)
    {
        $this->twitter_access_token = $twitterAccessToken;

        return $this;
    }

    /**
     * Get twitter_access_token
     *
     * @return string 
     */
    public function getTwitterAccessToken()
    {
        return $this->twitter_access_token;
    }   
}