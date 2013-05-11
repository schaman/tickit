<?php

namespace Tickit\PermissionBundle\Form\Type;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Tickit\PermissionBundle\Form\DataTransformer\PermissionToPermissionNameTransformer;

/**
 * User permissions form.
 *
 * Provides functionality for adding/editing permissions associated with a user.
 *
 * @package Tickit\PermissionBundle\Form\Type;
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class UserPermissionFormType extends AbstractType
{
    /**
     * The entity manager
     *
     * @var EntityManager
     */
    protected $em;

    /**
     * Constructor.
     *
     * @param Registry $doctrine The doctrine registry
     */
    public function __construct(Registry $doctrine)
    {
        $this->em = $doctrine->getManager();
    }

    /**
     * Builds the form.
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array                $options An array of form options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $transformer = new PermissionToPermissionNameTransformer($this->em);

        $permissionField = $builder->create('permission', 'text', array('label' => false))
                                   ->addModelTransformer($transformer);

        $builder->add($permissionField)
                ->add('value', 'checkbox', array('label' => false));
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'tickit_user_permission';
    }
}
