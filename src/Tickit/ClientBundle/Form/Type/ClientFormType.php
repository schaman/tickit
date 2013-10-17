<?php

namespace Tickit\ClientBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Client form type.
 *
 * Provides functionality for adding/editing clients.
 *
 * @package Tickit\ClientBundle\Form\Type
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class ClientFormType extends AbstractType
{
    /**
     * Builds the form
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array                $options An array of form options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', 'text')
                ->add('url', 'url', array('required' => false))
                ->add('notes', 'textarea', array('required' => false));
    }

    /**
     * Sets default form options
     *
     * @param OptionsResolverInterface $resolver The options resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(['data_class' => 'Tickit\ClientBundle\Entity\Client']);
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'tickit_client';
    }
}
