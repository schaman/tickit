<?php

namespace Tickit\PermissionBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Group permissions form.
 *
 * Provides functionality for editing permissions associated with a group.
 *
 * @package Tickit\PermissionBundle\Form\Type
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class GroupPermissionFormType extends AbstractType
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
        $builder->add('id', 'hidden')
                ->add('name', 'hidden')
                ->add('groupValue', 'checkbox', array('label' => false, 'required' => false));
    }

    /**
     * Sets default options for the form
     *
     * @param OptionsResolverInterface $resolver The options resolver
     *
     * @return void
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array('data_class' => 'Tickit\PermissionBundle\Model\Permission'));
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'tickit_group_permission';
    }
}
