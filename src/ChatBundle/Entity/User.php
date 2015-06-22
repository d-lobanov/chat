<?php

namespace ChatBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class User
 * @package ChatBundle\Entity
 */
class User extends BaseUser
{
    protected $id;
    protected $rooms;
    protected $roomsModerator;


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
     * Add rooms
     *
     * @param Room $rooms
     * @return User
     */
    public function addRoom(Room $rooms)
    {
        $this->rooms[] = $rooms;

        return $this;
    }

    /**
     * Remove rooms
     *
     * @param Room $rooms
     */
    public function removeRoom(Room $rooms)
    {
        $this->rooms->removeElement($rooms);
    }

    /**
     * Get rooms
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRooms()
    {
        return $this->rooms;
    }

    /**
     * Add roomsModerator
     *
     * @param Room $roomsModerator
     * @return User
     */
    public function addRoomsModerator(Room $roomsModerator)
    {
        $this->roomsModerator[] = $roomsModerator;

        return $this;
    }

    /**
     * Remove roomsModerator
     *
     * @param Room $roomsModerator
     */
    public function removeRoomsModerator(Room $roomsModerator)
    {
        $this->roomsModerator->removeElement($roomsModerator);
    }

    /**
     * Get roomsModerator
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRoomsModerator()
    {
        return $this->roomsModerator;
    }

    public function getUserRoomsAsArray()
    {
        $result = array();
        foreach ($this->rooms as $room) {
            $result[$room->getId()] = $room->getName();
        }

        return $result;
    }

    public function getModeratorRoomsAsArray()
    {
        $result = array();
        foreach ($this->roomsModerator as $room) {
            $result[$room->getId()] = $room->getName();
        }

        return $result;
    }

    public function getAllRoomsAsArray()
    {
        return $this->getUserRoomsAsArray() + $this->getModeratorRoomsAsArray();
    }

    public function hasRoom(Room $room)
    {
        return in_array($room, $this->rooms);
    }
}
