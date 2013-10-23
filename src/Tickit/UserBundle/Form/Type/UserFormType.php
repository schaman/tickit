<?php

/*
 * 
 * Tickit, an source web based bug management tool.
 * 
 * Copyright (C) 2013  Tickit Project <http://tickit.io>
 * 
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 * 
 */

namespace Tickit\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * User form.
 *
 * Provides functionality for adding/editing any user in the system.
 *
 * The built in ProfileFormType provided by FOSUserBundle is used for users who are
 * editing their own profile, but this form provides additional functionality
 *
 * @author James Halsall <james.t.halsall@googlemail.com>
 */
class UserFormType extends AbstractType
{
    /**
     * Builds the form.
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array                $options Additional options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $user = $builder->getData();

        if (null === $user) {
            $userId = '';
            $passwordLabel = 'Password';
        } else {
            $userId = $user->getId();
            $passwordLabel = 'New Password';
        }

        $builder->add('id', 'hidden', array('mapped' => false, 'data' => $userId))
                ->add('forename', 'text')
                ->add('surname', 'text')
                ->add('username', 'text')
                ->add('email', 'email')
                ->add(
                    'password',
                    'repeated',
                    array(
                        'type' => 'password',
                        'required' => false,
                        'first_options' => array('label' => $passwordLabel),
                        'second_options' => array('label' => 'Confirm ' . $passwordLabel),
                        'invalid_message' => 'Oops! Looks like those passwords don\'t match'
                    )
                )
                ->add('admin', 'checkbox', array('label' => 'Is admin?'));
    }

    /**
     * Sets the default options for this form
     *
     * @param OptionsResolverInterface $resolver The options resolver
     *
     * @return void
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $options = array('data_class' => 'Tickit\UserBundle\Entity\User');
        $resolver->setDefaults($options);
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'tickit_user';
    }
}
