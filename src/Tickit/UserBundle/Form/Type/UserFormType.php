<?php

namespace Tickit\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Tickit\UserBundle\Entity\User;

/**
 * User form.
 *
 * Provides functionality for adding/editing any user in the system.
 *
 * The built in ProfileFormType provided by FOSUserBundle is used for users who are
 * editing their own profile, but this form provides additional functionality
 *
 * @author James Halsall <james.t.halsall@googlemail.com>
 */
class UserFormType extends AbstractType
{
    /**
     * Builds the form.
     *
     * @param FormBuilderInterface $builder
     * @param array                $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('forename', 'text')
                ->add('surname', 'text')
                ->add('username', 'text')
                ->add('email', 'email')
                ->add('password', 'password')
                ->add('group', 'entity', array('class' => 'Tickit\UserBundle\Entity\Group'));
    }

    /**
     * Gets default options for this form type
     *
     * @param array $options Current options
     *
     * @return array
     */
    public function getDefaultOptions(array $options)
    {
        $options = array('data_class' => 'Tickit\UserBundle\Entity\User');

        return $options;
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'tickit_user';
    }
}
