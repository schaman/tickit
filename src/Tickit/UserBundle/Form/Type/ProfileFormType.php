<?php

namespace Tickit\UserBundle\Form\Type;

use FOS\UserBundle\Form\Type\ProfileFormType as BaseType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Form type used for adding and editing users in the system
 *
 * @package Tickit\UserBundle\Form\Type
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class ProfileFormType extends BaseType
{
    /**
     * {@inheritDoc}
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array                $options Any additional options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder->add('forename');
        $builder->add('surname');
    }

    /**
     * {@inheritDoc}
     *
     * @return string
     */
    public function getName()
    {
        return 'tickit_user_profile';
    }

}
