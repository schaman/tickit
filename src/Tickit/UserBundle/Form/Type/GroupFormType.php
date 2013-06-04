<?php

namespace Tickit\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Tickit\PermissionBundle\Form\Type\Field\GroupPermissionsType;
use Tickit\UserBundle\Form\EventListener\GroupFormSubscriber;

/**
 * Group form.
 *
 * Provides the ability to add/edit groups in the application.
 *
 * @package Tickit\UserBundle\Form\Type
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class GroupFormType extends AbstractType
{
    /**
     * Builds the form.
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array                $options Form options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', 'text')
                ->add('permissions', new GroupPermissionsType(), array('type' => 'tickit_group_permission'));

        $builder->addEventSubscriber(new GroupFormSubscriber());
    }

    /**
     * Sets default options on the form
     *
     * @param OptionsResolverInterface $resolver The options resolver
     *
     * @return void
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array('data_class' => 'Tickit\UserBundle\Entity\Group'));
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'tickit_group';
    }
}