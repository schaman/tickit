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

namespace Tickit\Bundle\ProjectBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Tickit\Bundle\ProjectBundle\Entity\AbstractAttribute;

/**
 * Choice attribute form.
 *
 * Provides functionality for editing choice attribute entities.
 *
 * @package Tickit\Bundle\ProjectBundle\Form\Type
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class ChoiceAttributeFormType extends AbstractAttributeFormType
{
    /**
     * Builds the form.
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array                $options Form options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('type', 'hidden', array('data' => AbstractAttribute::TYPE_CHOICE))
            ->add(
                'expanded',
                'choice',
                array(
                    'choices' => array(
                        1 => 'Yes',
                        0 => 'No'
                    ),
                    'expanded' => true,
                    'multiple' => false
                )
            )
            ->add(
                'allow_multiple',
                'choice',
                array(
                    'choices' => array(
                        1 => 'Yes',
                        0 => 'No'
                    ),
                    'expanded' => true,
                    'multiple' => false
                )
            )
            ->add(
                'choices',
                'collection',
                array(
                    'type' => new ChoiceAttributeChoiceFormType(),
                    'allow_delete' => true,
                    'allow_add' => true
                )
            );
    }

    /**
     * Sets default options for this form
     *
     * @param OptionsResolverInterface $resolver The options resolver
     *
     * @return void
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array('data_class' => 'Tickit\Bundle\ProjectBundle\Entity\ChoiceAttribute'));
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'tickit_project_attribute_choice';
    }
}
