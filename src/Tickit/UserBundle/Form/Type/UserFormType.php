<?php

namespace Tickit\UserBundle\Form\Type;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Tickit\PermissionBundle\Entity\Repository\PermissionRepository;
use Tickit\PermissionBundle\Entity\Repository\UserPermissionValueRepository;

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
     * The permissions repository
     *
     * @var PermissionRepository
     */
    protected $permissionsRepository;

    /**
     * Constructor.
     *
     * @param Registry $doctrine The doctrine registry
     */
    public function __construct(Registry $doctrine)
    {
        $this->permissionsRepository = $doctrine->getManager()->getRepository('TickitPermissionBundle:Permission');
    }

    /**
     * Builds the form.
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array                $options Additional options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $user = $builder->getData();

        if (null === $user) {
            $passwordLabel = 'Password';
        } else {
            $passwordLabel = 'New Password';
        }

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
                        'first_options' => array('label' => $passwordLabel),
                        'second_options' => array('label' => 'Confirm ' . $passwordLabel),
                        'invalid_message' => 'Oops! Looks like those passwords don\'t match'
                    )
                )
                ->add('group', 'entity', array('class' => 'Tickit\UserBundle\Entity\Group'));

        if (null === $user) {
            $builder->add(
                'permissions',
                'choice',
                array(
                    'choices' => $this->permissionsRepository->getAllAsKeyValuePairs(),
                    'expanded' => true,
                    'multiple' => true
                )
            );
        } else {
            $builder->add(
                'permissions',
                'entity',
                array(
                    'query_builder' => function($repo) use ($user) {
                        /** @var UserPermissionValueRepository $repo */
                        return $repo->findAllForUserQuery($user);
                    },
                    'class' => 'Tickit\PermissionBundle\Entity\UserPermissionValue',
                    'property' => 'permissionName',
                    'expanded' => true,
                    'multiple' => true
                )
            );
        }
    }

    /**
     * Sets the default options for this form
     *
     * @param OptionsResolverInterface $resolver The options resolver
     *
     * @return void
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $options = array('data_class' => 'Tickit\UserBundle\Entity\User');
        $resolver->setDefaults($options);
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
