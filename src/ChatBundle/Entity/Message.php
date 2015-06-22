<?php

namespace ChatBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class Message
 * @package ChatBundle\Entity
 */
class Message
{

    protected $id;
    protected $text;
    protected $created;
    protected $user;
    protected $room;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->user = new ArrayCollection();
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
     * Set text
     *
     * @param string $text
     * @return Message
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Add user
     *
     * @param User $user
     * @return Message
     */
    public function addUser(User $user)
    {
        $this->user[] = $user;

        return $this;
    }

    /**
     * Remove user
     *
     * @param User $user
     */
    public function removeUser(User $user)
    {
        $this->user->removeElement($user);
    }

    /**
     * Get user
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set user
     *
     * @param User $user
     * @return Message
     */
    public function setUser(User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Set room
     *
     * @param Room $room
     * @return Message
     */
    public function setRoom(Room $room = null)
    {
        $this->room = $room;

        return $this;
    }

    /**
     * Get room
     *
     * @return Room
     */
    public function getRoom()
    {
        return $this->room;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return Message
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    public function getInfo()
    {
        $info = array(
            'author' => $this->user->getUsername(),
            'time' => $this->created,
            'roomId' => $this->room->getId(),
            'id' => $this->id,
            'text' => $this->getText()
        );

        return $info;
    }
}
