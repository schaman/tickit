<?php

namespace Tickit\ProjectBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * ChoiceAttributeChoice form type.
 *
 * Provides an embeddable form object to allow the creation/editing of
 * choice attribute choices
 *
 * @package Tickit\ProjectBundle\Form\Type
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class ChoiceAttributeChoiceType extends AbstractType
{
    /**
     * Builds the form.
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array                $options An array of options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', 'text');
    }

    /**
     * Sets default options.
     *
     * @param OptionsResolverInterface $resolver The options resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array('data_class' => 'Tickit\ProjectBundle\Entity\ChoiceAttributeChoice'));
    }

    /**
     * Gets the name of this form type
     *
     * @return string
     */
    public function getName()
    {
        return 'tickit_project_attribute_choice_choice';
    }
}
