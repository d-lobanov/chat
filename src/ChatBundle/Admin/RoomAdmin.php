<?php

namespace ChatBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;


class RoomAdmin extends Admin
{
    // Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('name', 'text', array('label' => 'Name'))
            ->end()
            ->with('User')
                ->add('users', 'sonata_type_model', array(
                    'multiple' => true,
                    'required' => false
                ))
            ->end()
			->with('Moderator')
			->add('moderators', 'sonata_type_model', array(
				'multiple' => true,
				'required' => false
			))
			->end()
        ;
    }

    // Fields to be shown on filter forms
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('name')
			->add('users')
			->add('moderators')
        ;
    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('name')
			->addIdentifier('users')
			->addIdentifier('moderators')
        ;
    }
}