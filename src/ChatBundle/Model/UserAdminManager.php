<?php

namespace ChatBundle\Model;

use Sonata\DoctrineORMAdminBundle\Model;

class UserAdminManager extends Model\ModelManager
{

	protected $userManager;

	public function setUserManager($manager)
	{
		$this->userManager = $manager;
	}

	public function getUserManager()
	{
		return $this->userManager;
	}

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