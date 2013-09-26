<?php

namespace Tickit\ProjectBundle\Form\Type;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Tickit\ProjectBundle\Form\EventListener\AttributeValueFormSubscriber;

/**
 * Attribute value form type.
 *
 * Provides functionality for adding/editing values for attributes
 *
 * @package Tickit\ProjectBundle\Form\Type
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class AttributeValueFormType extends AbstractType
{
    /**
     * An entity manager
     *
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * Constructor.
     *
     * @param EntityManagerInterface $em An entity manager
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * Builds the form
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array                $options An array of options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // we let the event subscriber build the form for us
        $builder->addEventSubscriber(new AttributeValueFormSubscriber($this->em));
    }

    /**
     * Sets default options for the form
     *
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array('data_class' => 'Tickit\ProjectBundle\Entity\AbstractAttributeValue'));
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
