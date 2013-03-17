<?php

namespace Tickit\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Edit user form.
 *
 * Provides functionality for editing any user in the system. The built
 * in ProfileFormType provided by FOSUserBundle is used for users who are
 * editing their own profile, but this form provides additional functionality
 *
 * @author James Halsall <james.t.halsall@googlemail.com>
 */
class EditFormType extends AbstractType
{
    /**
     * Builds the form.
     *
     * @param FormBuilderInterface $builder
     * @param array                $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        //build form
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'tickit_user_edit';
    }
}