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

namespace Tickit\Bundle\IssueBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Tickit\Component\Model\Issue\Issue;

/**
 * Issue form type.
 *
 * Used for adding and editing issues.
 *
 * @package Tickit\Bundle\IssueBundle\Form\Type
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class IssueFormType extends AbstractType
{
    /**
     * Builds the form.
     *
     * @param FormBuilderInterface $builder A form builder
     * @param array                $options An array of form options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('number', 'text')
                ->add('title', 'text')
                ->add('description', 'textarea')
                ->add('project', 'tickit_project_picker', ['provider' => 'picker_project_find'])
                ->add('priority', 'choice', ['choices' => Issue::getValidPriorities(true)])
                ->add(
                    'type',
                    'entity',
                    [
                        'class' => 'TickitIssueBundle:IssueType',
                        'property' => 'name'
                    ]
                )
                ->add(
                    'status',
                    'entity',
                    [
                        'class' => 'TickitIssueBundle:IssueStatus',
                        'property' => 'name'
                    ]
                )
                ->add(
                    'attachments',
                    'collection',
                    [
                        'allow_add' => true,
                        'allow_delete' => true,
                        // TODO: create IssueAttachment form
                    ]
                )
                ->add('estimatedHours', 'text')
                ->add('actualHours', 'text')
                ->add('assignedTo', 'tickit_user_picker', ['provider' => 'picker_user_find']);
    }

    /**
     * Sets default form options
     *
     * @param OptionsResolverInterface $resolver An options resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(['data_class' => 'Tickit\Component\Model\Issue\Issue']);
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'tickit_issue';
    }
}
