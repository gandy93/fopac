<?php 
namespace FOPAC\ChatBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="permits")
 * @ORM\HasLifecycleCallbacks()
 */
class Permit {
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

	/**
	 * @ORM\Column(type="string", name="auth_hash", length=64, unique=true, nullable=false)
	 */
	protected $authHash;
	
	/**
	 * @ORM\ManyToOne(targetEntity="Room")
	 * @ORM\JoinColumn(name="room_id", referencedColumnName="id")
	 */
	protected $room;

	/**
	 * @ORM\Column(type="boolean", name="moderator", nullable=false)
	 */
	protected $moderator = false;

	/**
	 * @ORM\Column(type="datetime", name="created_at", nullable=false)
	 */
	protected $createdAt;
	
	/**
	 * @ORM\Column(type="datetime", name="last_access_at", nullable=true)
	 */
	protected $lastAccessAt = null;
	
	/**
     * @ORM\PrePersist
     */
    public function setCreatedAtValue()
    {
        $this->setCreatedAt(new \DateTime());
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
     * Set authHash
     *
     * @param string $authHash
     * @return Permit
     */
    public function setAuthHash($authHash)
    {
        $this->authHash = $authHash;
    
        return $this;
    }

    /**
     * Get authHash
     *
     * @return string 
     */
    public function getAuthHash()
    {
        return $this->authHash;
    }

    /**
     * Set moderator
     *
     * @param boolean $moderator
     * @return Permit
     */
    public function setModerator($moderator)
    {
        $this->moderator = $moderator;
    
        return $this;
    }

    /**
     * Get moderator
     *
     * @return boolean 
     */
    public function isModerator()
    {
        return $this->moderator;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Permit
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    
        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set lastAccessAt
     *
     * @param \DateTime $lastAccessAt
     * @return Permit
     */
    public function setLastAccessAt($lastAccessAt)
    {
        $this->lastAccessAt = $lastAccessAt;
    
        return $this;
    }

    /**
     * Get lastAccessAt
     *
     * @return \DateTime 
     */
    public function getLastAccessAt()
    {
        return $this->lastAccessAt;
    }

    /**
     * Set room
     *
     * @param \FOPAC\ChatBundle\Entity\Room $room
     * @return Permit
     */
    public function setRoom(\FOPAC\ChatBundle\Entity\Room $room = null)
    {
        $this->room = $room;
    
        return $this;
    }

    /**
     * Get room
     *
     * @return \FOPAC\ChatBundle\Entity\Room 
     */
    public function getRoom()
    {
        return $this->room;
    }

    /**
     * Get moderator
     *
     * @return boolean 
     */
    public function getModerator()
    {
        return $this->moderator;
    }
}