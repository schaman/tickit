<?php

/*
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
 */

namespace Tickit\UserBundle\Form\Type;

use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Tickit\UserBundle\Converter\UserEntityValueConverter;
use Tickit\UserBundle\Decorator\UserEntityDisplayNameDecorator;
use Tickit\UserBundle\Form\EventListener\UserPickerTypeSubscriber;
use Tickit\UserBundle\Manager\UserManager;
use Tickit\UserBundle\Tests\Converter\UserEntityValueConverterTest;

/**
 * User picker custom form field type
 *
 * @package Tickit\UserBundle\Form\Type
 * @author  Mark Wilson <mark@89allport.co.uk>
 */
class UserPickerType extends AbstractType
{
    // TODO: add role restriction
    const NO_RESTRICTION = 'none';
    const PROJECT_RESTRICTION = 'project';
    const CLIENT_RESTRICTION = 'client';

    /**
     * User manager service
     *
     * @var UserManager
     */
    private $userManager;

    /**
     * Constructor.
     *
     * @param UserManager $userManager User manager service
     */
    public function __construct(UserManager $userManager)
    {
        // TODO: should this be a decorator service to provide name from ID rather than a manager
        $this->userManager = $userManager;
    }

    /**
     * Build the form
     *
     * @param FormBuilderInterface $builder Form builder
     * @param array                $options Form options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // TODO: move attribute generation to a separate attribute builder class
        //       use values available in $builder to get the current content value to translate into display names
        
        // initialise text field's attributes
        $attributes = array(
            'data-restriction' => $options['picker_restriction']
        );

        // if there is a restriction, set the foreign ID in on the input field
        if ($options['picker_restriction'] !== self::NO_RESTRICTION) {
            $attributes['data-foreign-id'] = $options['foreign_id'];
        }

        // add the user Ids input field
        $builder->add(
            'user_ids',
            'text',
            array(
                'attr' => $attributes
            )
        );
    }

    /**
     * Set default field options
     *
     * @param OptionsResolverInterface $resolver
     *
     * @return void
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        // foreign Id is only relevant when there is a restriction set
        $resolver->setDefaults(
            array(
                'picker_restriction' => self::NO_RESTRICTION,
                'foreign_id'         => null,
                'compound'           => true
            )
        );
    }

    /**
     * Build form's view
     *
     * @param FormView      $view    Form view
     * @param FormInterface $form    Form to build
     * @param array         $options Form options
     *
     * @return void
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        /** @var Form $element */
        $element = $form->get('user_ids');
        $value   = $element->getData();

        if (!is_array($value)) {
            $value = explode(',', $value);
        }

        $userEntityValueConverter = new UserEntityValueConverter($this->userManager);

        $value = array_map(
            function ($userId) use ($userEntityValueConverter) {
                try {
                    return $userEntityValueConverter->convertUserIdToDisplayName($userId);
                } catch (EntityNotFoundException $e) {
                    return false;
                }
            },
            $value
        );

        $value = array_filter($value);

        $value               = implode(',', $value);
        $view->display_names = $value;
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

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'tickit_user_picker';
    }
}
