<?php

namespace ChatBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use ChatBundle\Entity;

/**
 * Class UserRepository
 * @package ChatBundle\Entity\Repository
 */
class UserRepository extends EntityRepository
{

    /**
     * @param int $userId
     * @return mixed
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\TransactionRequiredException
     */
    public function getRoomById($userId)
    {
        return $this->getEntityManager()
                ->find($this->getEntityName(), $userId)
                ->getAllRoomsAsArray();
    }
}
