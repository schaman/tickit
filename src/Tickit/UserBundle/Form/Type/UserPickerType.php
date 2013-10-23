<?php

namespace Tickit\UserBundle\Form\Type;

use Tickit\CoreBundle\Form\Type\Picker\AbstractPickerType;

/**
 * User picker custom form field type.
 *
 * @package Tickit\UserBundle\Form\Type
 * @author  Mark Wilson <mark@89allport.co.uk>
 */
class UserPickerType extends AbstractPickerType
{
    // TODO: add role restriction

    const PROJECT_RESTRICTION = 'project';
    const CLIENT_RESTRICTION = 'client';

    /**
     * Get extended field type
     *
     * @return string
     */
    public function getParent()
    {
        return 'text';
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'tickit_user_picker';
    }

    /**
     * Gets the name of the field that stores the picker IDs
     *
     * @return mixed
     */
    public function getFieldName()
    {
        return 'user_ids';
    }

    /**
     * Gets the restriction type (if any)
     *
     * @return string|null
     */
    public function getRestriction()
    {
        return static::NO_RESTRICTION;
    }
}
