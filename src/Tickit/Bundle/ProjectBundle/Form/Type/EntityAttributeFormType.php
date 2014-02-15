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

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Tickit\Component\Model\Project\AbstractAttribute;
use Tickit\Bundle\ProjectBundle\Form\Event\EntityAttributeFormBuildEvent;
use Tickit\Bundle\ProjectBundle\Form\TickitProjectFormEvents;

/**
 * Entity attribute form type.
 *
 * Provides functionality for editing entity type attributes
 *
 * @package Tickit\Bundle\ProjectBundle\Form\Type
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class EntityAttributeFormType extends AbstractAttributeFormType
{
    /**
     * An event dispatcher
     *
     * @var EventDispatcherInterface
     */
    protected $dispatcher;

    /**
     * Constructor.
     *
     * @param EventDispatcherInterface $dispatcher An event dispatcher
     */
    public function __construct(EventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * Builds the form.
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array                $options An array of options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $event = new EntityAttributeFormBuildEvent();
        $event = $this->dispatcher->dispatch(TickitProjectFormEvents::ENTITY_ATTRIBUTE_FORM_BUILD, $event);
        $choices = $event->getEntityChoices();

        $builder->add('type', 'hidden', array('data' => AbstractAttribute::TYPE_ENTITY))
                ->add(
                    'entity',
                    'choice',
                    array(
                        'choices' => $choices
                    )
                );
    }

    /**
     * Sets default options for this form
     *
     * @param OptionsResolverInterface $resolver The options resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array('data_class' => 'Tickit\Component\Model\Project\EntityAttribute'));
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'tickit_project_attribute_entity';
    }
}
