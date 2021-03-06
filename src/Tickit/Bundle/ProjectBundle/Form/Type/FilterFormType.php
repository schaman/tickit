<?php

/*
 * Tickit, an open source web based bug management tool.
 *
 * Copyright (C) 2014  Tickit Project <http://tickit.io>
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

namespace Tickit\Bundle\ProjectBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Tickit\Component\Model\Project\Project;

/**
 * Project filter form.
 *
 * Form type that encapsulates filter fields for projects.
 *
 * @package Tickit\Bundle\ProjectBundle\Form\Type
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class FilterFormType extends AbstractType
{
    /**
     * The form name
     */
    const NAME = 'tickit_project_filters';

    /**
     * Builds the form.
     *
     * @param FormBuilderInterface $builder A form builder
     * @param array                $options An array of form options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', 'text')
                ->add('owner', 'tickit_user_picker', ['provider' => 'picker_user_find'])
                ->add(
                    'status',
                    'choice',
                    ['choices' => Project::getStatusTypes(true)]
                )
                ->add('client', 'tickit_client_picker', ['provider' => 'picker_client_find']);
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return static::NAME;
    }
}
