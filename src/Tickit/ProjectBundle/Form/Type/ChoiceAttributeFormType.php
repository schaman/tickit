<?php

namespace Tickit\ProjectBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Choice attribute form.
 *
 * Provides functionality for editing choice attribute entities.
 *
 * @package Tickit\ProjectBundle\Form\Type
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class ChoiceAttributeFormType extends AbstractAttributeFormType
{
    /**
     * Builds the form.
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('expanded', 'choice', array(
                'choices' => array(
                    1 => 'Yes',
                    0 => 'No'
                ),
                'expanded' => true,
                'multiple' => false
            ))
            ->add('allow_multiple', 'choice', array(
                'choices' => array(
                    1 => 'Yes',
                    0 => 'No'
                ),
                'expanded' => true,
                'multiple' => false
            ))
            ->add('choices', 'collection', array(
            'type' => new ChoiceAttributeChoiceType(),
            'allow_delete' => true,
            'allow_add' => true
        ));
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
        $resolver->setDefaults(array('class' => 'Tickit\ProjectBundle\Entity\ChoiceAttribute'));
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
