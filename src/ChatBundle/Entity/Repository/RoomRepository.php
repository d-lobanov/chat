<?php

namespace ChatBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use ChatBundle\Entity;
use Proxies\__CG__\ChatBundle\Entity\Room;

/**
 * Class RoomRepository
 * @package ChatBundle\Entity\Repository
 */
class RoomRepository extends EntityRepository
{

    /**
     * @param int $id
     * @return bool|Room
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\TransactionRequiredException
     */
    public function existsRoomById($id)
    {
        $room = $this->find($id);
        if (is_null($room)) {
            return false;
        }

        return $room;
    }

    /**
     * @param string $name
     * @return bool|object
     */
    public function existsRoomByName($name)
    {
        $room = $this->findOneBy(array('name' => $name));
        if (is_null($room)) {
            return false;
        }

        return $room;
    }

    /**
     * @param int $roomId
     * @param int $userId
     * @return bool
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\TransactionRequiredException
     */
    public function isModerator($roomId, $userId)
    {
        $isModerator = false;
        if ($room = $this->existsRoomById($roomId)) {
            $isModerator = $room->getModerators()
                ->exists(
                    function ($key, $element) use ($userId) {
                        return ($element->getId() == $userId);
                    }
                );
        }

        return (bool) $isModerator;
    }

    /**
     * @param int $roomId
     * @param int $userId
     * @return bool
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\TransactionRequiredException
     */
    public function isUser($roomId, $userId)
    {
        $isUser = false;
        $isModerator = $this->isModerator($roomId, $userId);

        if ($room = $this->existsRoomById($roomId)) {
            $isUser = $room->getUsers()
                ->exists(
                    function ($key, $element) use ($userId) {
                        return ($element->getId() == $userId);
                    }
                );
        }

        return (bool) ($isUser || $isModerator);
    }

    /**
     * @param int $roomId
     * @return array
     */
    public function getMessages($roomId)
    {
        $em = $this->getEntityManager();
        $query = $em->createQueryBuilder()
            ->select('m.id, m.text, m.created, u.username as author')
            ->from('ChatBundle:room', 'r')
            ->innerJoin('ChatBundle:Message', 'm', 'WITH', 'm.room = r')
            ->innerJoin('ChatBundle:user', 'u', 'WITH', 'm.user = u')
            ->where('r.id = :room_id')
            ->orderBy('m.created', 'ASC')
            ->setParameters(array('room_id' => $roomId));

        return $query->getQuery()->getResult();
    }

    /**
     * @param int $roomId
     * @return array
     */
    public function getMessagesGroupByDate($roomId)
    {
        $result = array();
        $messages = $this->getMessages($roomId);
        if (empty($messages)) {
            return $result;
        }

        $tempDate = $messages[0]['created'];
        $index = $tempDate->format('Y-m-d');

        foreach ($messages as $message) {
            $interval = $message['created']->diff($tempDate);
            if ($interval->invert) {
                $tempDate = $message['created'];
                $index = $tempDate->format('Y-m-d');
            }
            $result[$index][] = $message;
        }

        return $result;
    }
}
