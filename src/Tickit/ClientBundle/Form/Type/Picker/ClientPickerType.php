<?php

namespace Tickit\ClientBundle\Form\Type\Picker;

use Tickit\CoreBundle\Form\Type\Picker\AbstractPickerType;

/**
 * Client picker field type.
 *
 * Provides a field for picking a single client.
 *
 * @package Tickit\ClientBundle\Form\Type\Picker
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class ClientPickerType extends AbstractPickerType
{
    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'tickit_client_picker';
    }
}
 