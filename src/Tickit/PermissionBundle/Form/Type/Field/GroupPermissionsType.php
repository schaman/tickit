<?php

namespace Tickit\PermissionBundle\Form\Type\Field;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Group permissions field type.
 *
 * Field type used for rendering permissions for a group object.
 *
 * @package Tickit\PermissionBundle\Form\Type\Field
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class GroupPermissionsType extends AbstractType
{
    /**
     * Sets default form options
     *
     * @param OptionsResolverInterface $resolver The options resolver
     *
     * @return void
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array('prototype' => false, 'allow_add' => false));
    }

    /**
     * Returns the parent form type
     *
     * @return string
     */
    public function getParent()
    {
        return 'collection';
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'group_permissions';
    }
}
