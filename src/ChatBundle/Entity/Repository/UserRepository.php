<?php

namespace ChatBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use ChatBundle\Entity;

class UserRepository extends EntityRepository
{

    public function getRoomById($userId)
    {
        return $this->getEntityManager()
                ->find($this->getEntityName(), $userId)
                ->getRoomsAsArray();
    }

}