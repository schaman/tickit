<?php

namespace Tickit\ProjectBundle\Form\Type;

use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Tickit\ProjectBundle\Entity\AbstractAttribute;
use Tickit\ProjectBundle\Form\Event\EntityAttributeFormBuildEvent;
use Tickit\ProjectBundle\Form\TickitProjectFormEvents;

/**
 * Entity attribute form type.
 *
 * Provides functionality for editing entity type attributes
 *
 * @package Tickit\ProjectBundle\Form\Type
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class EntityAttributeFormType extends AbstractAttributeFormType
{
    /**
     * The event dispatcher
     *
     * @var EventDispatcher
     */
    protected $dispatcher;

    /**
     * Constructor.
     *
     * @param EventDispatcher $dispatcher The event dispatcher
     */
    public function __construct(EventDispatcher $dispatcher)
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
        $resolver->setDefaults(array('data_class' => 'Tickit\ProjectBundle\Entity\EntityAttribute'));
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
