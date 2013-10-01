<?php

namespace Tickit\UserBundle\Form\Type;

use FOS\UserBundle\Form\Type\RegistrationFormType as BaseType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Registration form.
 *
 * @package Tickit\UserBundle\Form\Type
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class RegistrationFormType extends BaseType
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

        $builder->add('forename', 'text')
                ->add('surname', 'text')
                ->add('username', 'text')
                ->add('email', 'email')
                ->add(
                    'password',
                    'repeated',
                    array(
                        'type' => 'password',
                        'required' => false,
                        'first_options' => array('label' => 'Password'),
                        'second_options' => array('label' => 'Confirm Password'),
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
        return 'tickit_user_registration';
    }
}
