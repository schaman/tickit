<?php

namespace Tickit\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Tickit\UserBundle\Entity\User;

/**
 * Edit user form.
 *
 * Provides functionality for editing any user in the system. The built
 * in ProfileFormType provided by FOSUserBundle is used for users who are
 * editing their own profile, but this form provides additional functionality
 *
 * @author James Halsall <james.t.halsall@googlemail.com>
 */
class EditFormType extends AbstractType
{
    /**
     * The user entity that is being edited
     *
     * @var User
     */
    protected $user;

    /**
     * Constructor.
     *
     * @param User $user The user entity to edit
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

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
        $builder->add('id', 'hidden', array('data' => $this->user->getId()))
                ->add('username', 'text', array('data' => $this->user->getUsername()))
                ->add('email', 'email', array('data' => $this->user->getEmail()));
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'tickit_user_edit';
    }
}