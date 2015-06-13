<?php

namespace ChatBundle\Admin;

use ChatBundle\ChatBundle;
use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use ChatBundle\Entity;


class UserAdmin extends Admin
{

    protected function getRepository()
    {
        $em = $this->getConfigurationPool()->getContainer()->get('doctrine')->getManager();
        return $em->getRepository($this->getClass());
    }

    // Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper)
    {
        $roles = $this->getRepository()->getRoleByArray();

        $formMapper
            ->add('username', 'text', array('label' => 'Username'))
            ->add('roles','choice',
                array(
                    'choices' => $roles,
                    'multiple'=> true,
                    'expanded' => false,
                    'required' => false
                )
            )
            ->add('password', 'text',
                array(
                    'required' => false,
                    'data' => ''
                )
            )
        ;
    }

    // Fields to be shown on filter forms
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('username')
        ;
    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('username')
        ;
    }

    public function create($object)
    {
        $this->prePersist($object);

        $username   = $object->getUsername();
        $password   = $object->getPassword();
        $roles      = $object->getRoles();

        $manipulator = $this->getConfigurationPool()->getContainer()->get('chat.user_manipulator');
        $manipulator->create($username, $password);
        $manipulator->addRoles($username, $roles);

        $object = $this->getRepository()->findOneByUsername($username);

        $this->postPersist($object);

        $this->createObjectSecurity($object);

        return $object;
    }

}