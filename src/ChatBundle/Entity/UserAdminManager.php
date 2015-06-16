<?php

namespace ChatBundle\Entity;

use Sonata\DoctrineORMAdminBundle\Model;

class UserAdminManager extends Model\ModelManager
{

	protected $userManager;

	public function setUserManager($manager)
	{
		$this->entityManager = $manager;
	}

	public function getUserManager()
	{
		return $this->entityManager;
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

		$user = $this->getUserManager()->createUser();
		$user->setUsername($username);
		if(!empty($password)){
			$user->setPlainPassword($password);
		}
		$this->getUserManager()->updateUser($user);

		return $user;
	}

	public function delete($object)
	{
		die('delete');
	}

}