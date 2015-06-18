<?php

namespace ChatBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use ChatBundle\Entity;

/**
 * Class RoomRepository
 * @package ChatBundle\Entity\Repository
 */
class RoomRepository extends EntityRepository
{

    /**
     * @param int $roomId
     * @param int $userId
     * @return mixed
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\TransactionRequiredException
     */
    public function isModerator($roomId, $userId)
    {
        $isModerator = $this->getEntityManager()
            ->find($this->getEntityName(), $roomId)
            ->getModerators()
            ->exists(
                function ($key, $element) use ($userId) {
                    return ($element->getId() == $userId);
                }
            );

        return $isModerator;
    }

    /**
     * @param int $roomId
     * @param int $userId
     * @return mixed
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\TransactionRequiredException
     */
    public function isUser($roomId, $userId)
    {
        $isUser = $this->getEntityManager()
            ->find($this->getEntityName(), $roomId)
            ->getUsers()
            ->exists(
                function ($key, $element) use ($userId) {
                    return ($element->getId() == $userId);
                }
            );

        return $isUser;
    }
}
