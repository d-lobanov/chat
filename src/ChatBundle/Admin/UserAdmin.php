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

	/**
	 * Fields to be shown on create/edit forms
	 * @param FormMapper $formMapper
	 */
    protected function configureFormFields(FormMapper $formMapper)
    {

        $formMapper
            ->add('username', 'text', array('label' => 'Username'))
            ->add('password', 'text', array(
                    'required' => true,
                    'data' => ''
			))
        ;
    }

	/**
	 * Fields to be shown on filter forms
	 * @param DatagridMapper $datagridMapper
	 */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('username')
            ->add('rooms', null, array(
				'field_name' => 'Included in'
			))
			->add('roomsModerator', null, array(
				'field_name' => 'Moderator in'
			))
        ;
    }

	/**
	 * Fields to be shown on lists
	 * @param ListMapper $listMapper
	 */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('username')
			->addIdentifier('rooms')
			->addIdentifier('roomsModerator')
        ;
    }

}