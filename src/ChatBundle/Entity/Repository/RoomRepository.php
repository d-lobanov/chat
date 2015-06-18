<?php

namespace ChatBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use ChatBundle\Entity;

class RoomRepository extends EntityRepository
{

    public function isModerator($roomId, $userId)
    {
        $isModerator = $this->getEntityManager()
            ->find($this->getEntityName(), $roomId)
            ->getModerators()
            ->exists(
                function($key, $element) use ($userId) {
                    return ($element->getId() == $userId);
                }
            );

        return $isModerator;
    }

    public function isUser($roomId, $userId)
    {
        $isUser = $this->getEntityManager()
            ->find($this->getEntityName(), $roomId)
            ->getUsers()
            ->exists(
                function($key, $element) use ($userId) {
                    return ($element->getId() == $userId);
                }
            );

        return $isUser;
    }

}