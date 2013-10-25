<?php

namespace Tickit\UserBundle\Form\Type\Picker;

use Tickit\CoreBundle\Form\Type\Picker\AbstractPickerType;

/**
 * User picker custom form field type.
 *
 * @package Tickit\UserBundle\Form\Type\Picker
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
}
