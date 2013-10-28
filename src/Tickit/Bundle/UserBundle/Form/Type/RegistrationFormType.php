<?php

/*
 * Tickit, an open source web based bug management tool.
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
 */

namespace Tickit\Bundle\UserBundle\Form\Type;

use FOS\UserBundle\Form\Type\RegistrationFormType as BaseType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Registration form.
 *
 * @package Tickit\Bundle\UserBundle\Form\Type
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class RegistrationFormType extends BaseType
{
    /**
     * {@inheritDoc}
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array                $options Any additional options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder->add('forename', 'text')
                ->add('surname', 'text')
                ->add('username', 'text')
                ->add('email', 'email')
                ->add(
                    'password',
                    'repeated',
                    array(
                        'type' => 'password',
                        'required' => false,
                        'first_options' => array('label' => 'Password'),
                        'second_options' => array('label' => 'Confirm Password'),
                        'invalid_message' => 'Oops! Looks like those passwords don\'t match'
                    )
                );
    }

    /**
     * {@inheritDoc}
     *
     * @return string
     */
    public function getName()
    {
        return 'tickit_user_registration';
    }
}
