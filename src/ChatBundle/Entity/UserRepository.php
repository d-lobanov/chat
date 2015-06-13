<?php

namespace ChatBundle\Entity;

use Doctrine\ORM\EntityRepository;
use ChatBundle\Entity;

class UserRepository extends EntityRepository
{
    public function getRoleByArray(){
        return array(
            'ROLE_USER' => 'User',
            'ROLE_MODERATOR' => 'Moderator'
        );
    }


}