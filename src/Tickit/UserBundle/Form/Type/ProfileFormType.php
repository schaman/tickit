<?php

namespace Tickit\UserBundle\Form\Type;

use FOS\UserBundle\Form\Type\ProfileFormType as BaseType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Profile form.
 *
 * Used by authenticated users to edit their profile information in the application.
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

        $builder->add('forename')
                ->add('surname')
                ->add(
                    'password',
                    'repeated',
                    array(
                        'type' => 'password',
                        'required' => false,
                        'first_options' => array('label' => 'New Password'),
                        'second_options' => array('label' => 'Confirm New Password'),
                        'invalid_message' => 'Oops! Looks like those passwords don\'t match'
                    )
                );
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
