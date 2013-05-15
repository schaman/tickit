<?php

namespace Tickit\PermissionBundle\Form\Type\Field;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Permissions field type.
 *
 * Field used for rendering permissions data
 *
 * @package Tickit\PermissionBundle\Form\Type\Field
 * @author  James Halsall <james.t.halsall@googlemail.com>
 * @see     Tickit\PermissionBundle\Form\UserPermissionFormType
 * @see     Tickit\PermissionBundle\Mode\Permission
 */
class PermissionsType extends AbstractType
{
    /**
     * Gets the field that this field extends
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
        return 'permissions';
    }
}