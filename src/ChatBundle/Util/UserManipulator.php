<?php

namespace ChatBundle\Util;

use FOS\UserBundle\Util;
use FOS\UserBundle\Model\UserManagerInterface;

class UserManipulator extends Util\UserManipulator
{

    public function create($username, $password, $email = '', $active = true, $superadmin = false)
    {
        parent::create($username, $password, $email, $active, $superadmin);
    }

    /**
     * @param $username
     * @param array $roles
     * @return Boolean
     */
    public function addRoles($username, $roles)
    {
        foreach($roles as $role){
            if($this->addRole($username, $role) == false)
            {
                return false;
            }
        }
        return true;
    }

}