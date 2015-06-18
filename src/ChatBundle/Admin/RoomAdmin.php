<?php

namespace ChatBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

/**
 * Class RoomAdmin
 * @package ChatBundle\Admin
 */
class RoomAdmin extends Admin
{

    /**
     * Fields to be shown on create/edit forms
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('name', 'text', array('label' => 'Name'))
            ->end()
            ->with('User')
                ->add('users', 'sonata_type_model', array(
                    'multiple' => true,
                    'required' => false,
                ))
            ->end()
            ->with('Moderator')
            ->add('moderators', 'sonata_type_model', array(
                'multiple' => true,
                'required' => false,
            ))
            ->end()
        ;
    }

    /**
     * Fields to be shown on filter forms
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('name')
            ->add('users')
            ->add('moderators')
        ;
    }

    /**
     * Fields to be shown on lists
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('name')
            ->addIdentifier('users')
            ->addIdentifier('moderators')
        ;
    }
}
