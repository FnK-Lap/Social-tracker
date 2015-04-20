<?php

namespace SocialTracker\Bundle\ApplicationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * YoutubePost
 */
class YoutubePost
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
    private $youtubeId;

    /**
     * @var \DateTime
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
     * @return YoutubePost
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
     * Set youtubeId
     *
     * @param string $youtubeId
     * @return YoutubePost
     */
    public function setYoutubeId($youtubeId)
    {
        $this->youtubeId = $youtubeId;

        return $this;
    }

    /**
     * Get youtubeId
     *
     * @return string 
     */
    public function getYoutubeId()
    {
        return $this->youtubeId;
    }

    /**
     * Set createdTime
     *
     * @param \DateTime $createdTime
     * @return YoutubePost
     */
    public function setCreatedTime($createdTime)
    {
        $this->createdTime = $createdTime;

        return $this;
    }

    /**
     * Get createdTime
     *
     * @return \DateTime 
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
     * @return YoutubePost
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
