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

namespace Tickit\Bundle\CoreBundle\Form\Type\Picker;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Tickit\Bundle\CoreBundle\Form\Type\Picker\DataTransformer\AbstractPickerDataTransformer;

/**
 * Abstract Picker field type.
 *
 * This field type is used to create a picker component for entities in
 * the application.
 *
 * @package Tickit\Bundle\CoreBundle\Form\Type\Picker
 * @author  Mark Wilson <mark@89allport.co.uk>
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
abstract class AbstractPickerType extends AbstractType
{
    /**
     * A data transformer for the picker
     *
     * @var AbstractPickerDataTransformer
     */
    protected $transformer;

    /**
     * Constructor.
     *
     * @param AbstractPickerDataTransformer $transformer A data transformer for the picker
     */
    public function __construct(AbstractPickerDataTransformer $transformer)
    {
        $this->transformer = $transformer;
    }

    /**
     * Build the form
     *
     * @param FormBuilderInterface $builder Form builder
     * @param array                $options Form options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->transformer->setMaxSelections($options['max_selections']);
        $builder->addModelTransformer($this->transformer);
    }

    /**
     * Builds the form view
     *
     * @param FormView      $view    The form view
     * @param FormInterface $form    The form
     * @param array         $options An array of options for the form
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['attr'] = array(
            'class' => 'picker',
            'data-max-selections' => (intval($options['max_selections']) < 0) ? 0 : $options['max_selections'],
            'data-provider' => $options['provider']
        );
    }

    /**
     * Set default field options
     *
     * @param OptionsResolverInterface $resolver An options resolver
     *
     * @throws \InvalidArgumentException If an invalid restriction type is specified
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setRequired(['provider'])
                 ->setOptional(['max_selections'])
                 // the default value for max_selection is 0, which indicates no limit
                 ->setDefaults(['max_selections' => 0])
                 ->setAllowedTypes(['max_selections' => ['null', 'integer'], 'provider' => 'string']);
    }

    /**
     * Get extended field type
     *
     * @return string
     */
    public function getParent()
    {
        return 'text';
    }
}
