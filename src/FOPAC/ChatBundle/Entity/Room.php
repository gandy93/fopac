<?php 
namespace FOPAC\ChatBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
* @ORM\Entity
* @ORM\Table(name="rooms")
* @ORM\HasLifecycleCallbacks()
*/
class Room
{
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer", name="id")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */	
	protected $id;
	
	/**
	 * @ORM\Column(type="string", name="title", length=64, unique=false, nullable=false)
	 */
	protected $title;

	/**
	 * @ORM\Column(type="text", name="description")
	 */
	protected $description = '';
	
	/**
	 * @ORM\Column(type="string", name="passphrase", length=64, unique=false, nullable=false)
	 */
	protected $passphrase = '';

    /**
     * @ORM\OneToMany(targetEntity="Permit", mappedBy="room", cascade={"remove"})
     */
    protected $permits;

    /**
     * @ORM\OneToMany(targetEntity="Message", mappedBy="room", cascade={"remove"})
     */
    protected $messages;
    

    /**
     * @ORM\Column(type="datetime", name="created_at", nullable=false)
     */
    protected $createdAt;

    /**
     * @ORM\Column(type="datetime", name="last_access_at", nullable=true)
     */
    protected $lastAccessAt = null;
    
    
	public function _construct()
    {
        $this->permits = new ArrayCollection();
        $this->messages = new ArrayCollection();
    }

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
     * Set title
     *
     * @param string $title
     * @return Room
     */
    public function setTitle($title)
    {
        $this->title = $title;
    
        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Room
     */
    public function setDescription($description)
    {
        $this->description = $description;
    
        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set passphrase
     *
     * @param string $passphrase
     * @return Room
     */
    public function setPassphrase($passphrase)
    {
        $this->passphrase = $passphrase;
    
        return $this;
    }

    /**
     * Get passphrase
     *
     * @return string 
     */
    public function getPassphrase()
    {
        return $this->passphrase;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Room
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
     * @return Room
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
     * Constructor
     */
    public function __construct()
    {
        $this->permits = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Add permits
     *
     * @param \FOPAC\ChatBundle\Entity\Permit $permits
     * @return Room
     */
    public function addPermit(\FOPAC\ChatBundle\Entity\Permit $permits)
    {
        $this->permits[] = $permits;
    
        return $this;
    }

    /**
     * Remove permits
     *
     * @param \FOPAC\ChatBundle\Entity\Permit $permits
     */
    public function removePermit(\FOPAC\ChatBundle\Entity\Permit $permits)
    {
        $this->permits->removeElement($permits);
    }

    /**
     * Get permits
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPermits()
    {
        return $this->permits;
    }

    /**
     * Add messages
     *
     * @param \FOPAC\ChatBundle\Entity\Message $messages
     * @return Room
     */
    public function addMessage(\FOPAC\ChatBundle\Entity\Message $messages)
    {
        $this->messages[] = $messages;
    
        return $this;
    }

    /**
     * Remove messages
     *
     * @param \FOPAC\ChatBundle\Entity\Message $messages
     */
    public function removeMessage(\FOPAC\ChatBundle\Entity\Message $messages)
    {
        $this->messages->removeElement($messages);
    }

    /**
     * Get messages
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMessages()
    {
        return $this->messages;
    }
}