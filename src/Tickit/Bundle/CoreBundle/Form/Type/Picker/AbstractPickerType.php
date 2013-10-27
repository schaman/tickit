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

use Doctrine\Common\Collections\ArrayCollection;
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
     * No restrictions.
     *
     * This indicates that multiple selections can be made.
     */
    const RESTRICTION_NONE = 'none';

    /**
     * Single selection restriction.
     *
     * This indicates that only one selection can be made.
     */
    const RESTRICTION_SINGLE = 'single';

    /**
     * An entity decorator
     *
     * @var EntityDecoratorInterface
     */
    protected $decorator;

    /**
     * A data transformer for the picker
     *
     * @var AbstractPickerDataTransformer
     */
    protected $transformer;

    /**
     * Constructor.
     *
     * @param EntityDecoratorInterface      $entityDecorator An entity decorator
     * @param AbstractPickerDataTransformer $transformer     A data transformer for the picker
     */
    public function __construct(EntityDecoratorInterface $entityDecorator, AbstractPickerDataTransformer $transformer)
    {
        $this->decorator = $entityDecorator;
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
        // TODO: move attribute generation to a separate attribute builder class for custom attributes

        // initialise text field's attributes
        $attributes = array(
            'data-restriction' => $options['picker_restriction']
        );

        // we set the restriction as a data-* attribute, this lets the JS do the
        // client side restriction and our field validator do the server-side work
        if ($options['picker_restriction'] !== self::RESTRICTION_NONE) {
            $attributes['data-restriction'] = $options['picker_restriction'];
        }

        $this->transformer->setRestriction($options['picker_restriction']);

        $builder->addModelTransformer($this->transformer)
                ->setAttributes($attributes);
    }

    /**
     * Build form's view
     *
     * @param FormView      $view    Form view
     * @param FormInterface $form    Form to build
     * @param array         $options Form options
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $value = $form->getData();

        if ($value instanceof ArrayCollection) {
            $value = $value->toArray();
        } else {
            $value = [$value];
        }

        $value = array_map(
            function ($entity) {
                return $this->decorator->decorate($entity);
            },
            $value
        );

        $value = array_filter($value);

        $value               = implode(',', $value);
        $view->displayValues = $value;
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
        $resolver->setOptional(['picker_restriction'])
                 ->setDefaults(['picker_restriction' => static::RESTRICTION_NONE])
                 ->setAllowedValues(
                     ['picker_restriction' => [static::RESTRICTION_NONE, static::RESTRICTION_SINGLE]]
                 );
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
