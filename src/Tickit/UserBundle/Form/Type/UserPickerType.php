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
}
