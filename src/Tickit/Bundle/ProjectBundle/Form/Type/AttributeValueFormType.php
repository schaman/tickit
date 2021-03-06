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
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Tickit\Bundle\ProjectBundle\Form\EventListener\AttributeValueFormSubscriber;

/**
 * Attribute value form type.
 *
 * Provides functionality for adding/editing values for attributes
 *
 * @package Tickit\Bundle\ProjectBundle\Form\Type
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class AttributeValueFormType extends AbstractType
{
    /**
     * Builds the form
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array                $options An array of options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // we let the event subscriber build the form for us
        $builder->addEventSubscriber(new AttributeValueFormSubscriber());
    }

    /**
     * Sets default options for the form
     *
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array('data_class' => 'Tickit\Component\Model\Project\AbstractAttributeValue'));
    }

    /**
     * Returns the name of this type.
     *
     * @return string
     */
    public function getName()
    {
        return 'tickit_project_attribute_value';
    }
}
