<?php

namespace Tickit\PermissionBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Tickit\UserBundle\Entity\User;

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
     * Builds the form.
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array                $options An array of form options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('permission', 'text')
                ->add('value', 'checkbox');
    }

    /**
     * Sets form options.
     *
     * @param OptionsResolverInterface $resolver The options resolver.
     *
     * @return void
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array('data_class' => 'Tickit\PermissionBundle\Entity\UserPermissionValue'));
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
