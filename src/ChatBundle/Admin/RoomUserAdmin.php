<?php

namespace ChatBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;


class RoomUserAdmin extends Admin
{
    protected $parentAssociationMapping = 'room';

    // Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('room_id')
        ;

        $formMapper->end();
    }

    // Fields to be shown on filter forms
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('name')
        ;
    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        if (!$this->isChild())
            $listMapper->addIdentifier('id')->addIdentifier('Room');

        $listMapper
            ->addIdentifier('room_id')
        ;
    }
}