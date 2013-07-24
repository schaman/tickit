<?php

namespace Tickit\TeamBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Add/edit team form.
 *
 * Provides functionality for adding/editing team entities.
 *
 * @package Tickit\TeamBundle\Form\Type
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class TeamFormType extends AbstractType
{
    /**
     * Builds the form.
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array                $options Form options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', 'text');
    }

    /**
     * Sets default options for this form type
     *
     * @param OptionsResolverInterface $resolver The options resolver
     *
     * @return array
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array('data_class' => 'Tickit\TeamBundle\Entity\Team'));
    }

    /**
     * Returns the name of this type.
     *
     * @return string
     */
    public function getName()
    {
        return 'tickit_team';
    }
}
