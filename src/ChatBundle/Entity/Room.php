<?php

namespace ChatBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use ChatBundle\Entity;

/**
 * @ORM\Entity
 * @ORM\Table(name="room")
 */
class Room {

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=100, unique=true)
     */
    protected $name;

    /**
     * @ORM\ManyToMany(targetEntity="User", inversedBy="rooms")
     * @ORM\JoinTable(name="room_user",
     *      joinColumns={@ORM\JoinColumn(name="room_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")}
     *      )
     */
    protected $users;

	/**
	 * @ORM\ManyToMany(targetEntity="User", inversedBy="roomsModerator")
	 * @ORM\JoinTable(name="room_moderator",
	 *      joinColumns={@ORM\JoinColumn(name="room_id", referencedColumnName="id")},
	 *      inverseJoinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")}
	 *      )
	 */
	protected $moderators;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->users = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set name
     *
     * @param string $name
     * @return Room
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Add users
     *
     * @param \ChatBundle\Entity\User $users
     * @return Room
     */
    public function addUser(\ChatBundle\Entity\User $users)
    {
        $this->users[] = $users;

        return $this;
    }

    /**
     * Remove users
     *
     * @param \ChatBundle\Entity\User $users
     */
    public function removeUser(\ChatBundle\Entity\User $users)
    {
        $this->users->removeElement($users);
    }

    /**
     * Get users
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * Add moderators
     *
     * @param \ChatBundle\Entity\User $moderators
     * @return Room
     */
    public function addModerator(\ChatBundle\Entity\User $moderators)
    {
        $this->moderators[] = $moderators;

        return $this;
    }

    /**
     * Remove moderators
     *
     * @param \ChatBundle\Entity\User $moderators
     */
    public function removeModerator(\ChatBundle\Entity\User $moderators)
    {
        $this->moderators->removeElement($moderators);
    }

    /**
     * Get moderators
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getModerators()
    {
        return $this->moderators;
    }

	public function __toString()
	{
		return (string) $this->getName();
	}
}
