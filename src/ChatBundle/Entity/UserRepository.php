<?php

namespace ChatBundle\Entity;

use Doctrine\ORM\EntityRepository;
use ChatBundle\Entity;

class UserRepository extends EntityRepository
{
    public function getRoleAsArray()
    {
        return array(
            'ROLE_USER' => 'User',
            'ROLE_MODERATOR' => 'Moderator'
        );
    }

    public function getRoomById($userId)
    {
        return $this->getEntityManager()
                ->find($this->getEntityName(), $userId)
                ->getRoomsAsArray();
    }

}