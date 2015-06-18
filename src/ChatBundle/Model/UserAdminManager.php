<?php

namespace ChatBundle\Model;

use Sonata\DoctrineORMAdminBundle\Model;

/**
 * Class UserAdminManager
 * @package ChatBundle\Model
 */
class UserAdminManager extends Model\ModelManager
{

    /**
     * @var
     */
    protected $userManager;

    /**
     * @param $manager
     */
    public function setUserManager($manager)
    {
        $this->userManager = $manager;
    }

    /**
     * @return mixed
     */
    public function getUserManager()
    {
        return $this->userManager;
    }

    /**
     * @param $object
     * @return mixed
     */
    public function create($object)
    {
        $username   = $object->getUsername();
        $password   = $object->getPassword();

        $user = $this->getUserManager()->createUser();
        $user->setUsername($username);
        $user->setPlainPassword($password);
        $user->setEnabled(true);
        $user->setSuperAdmin(false);
        $this->getUserManager()->updateUser($user);

        return $user;
    }

    /**
     * @param $object
     * @return mixed
     */
    public function update($object)
    {
        $username   = $object->getUsername();
        $password   = $object->getPassword();

        $criteria = array('id' => $object->getId());
        $user = $this->getUserManager()->findUserBy($criteria);
        $user->setUsername($username);
        $user->setPlainPassword($password);
        $this->getUserManager()->updateUser($user);

        return $user;
    }
}
