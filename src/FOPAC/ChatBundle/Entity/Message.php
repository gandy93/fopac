<?php 
namespace FOPAC\ChatBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="messages")
 */
class Message {
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

	/**
	 * @ORM\ManyToOne(targetEntity="Room")
	 * @ORM\JoinColumn(name="room_id", referencedColumnName="id")
	 */
	protected $room;
	
	/**
	 * @ORM\Column(type="string", name="nickname", length=64, unique=false, nullable=false)
	 */
	protected $nickname;

	/**
	 * @ORM\Column(type="text", name="content", nullable=false)
	 */
	protected $content = '';
	
	/**
	 * @ORM\Column(type="datetime", name="created_at", nullable=false)
	 */
	protected $createdAt;
	
	
}

 ?>