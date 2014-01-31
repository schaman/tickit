<?php

/*
 * Tickit, an open source web based bug management tool.
 *
 * Copyright (C) 2014  Tickit Project <http://tickit.io>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Tickit\Bundle\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Security\Core\Role\RoleInterface;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Tickit\Component\Security\Role\Decorator\RoleDecoratorInterface;
use Tickit\Component\Security\Role\Provider\RoleProviderInterface;

/**
 * Roles form type.
 *
 * A field type for managing roles on a user.
 *
 * @package Tickit\Bundle\UserBundle\Form\Type
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class RolesFormType extends AbstractType
{
    /**
     * An array of all available roles
     *
     * @var RoleProviderInterface
     */
    private $roleProvider;

    /**
     * A role decorator
     *
     * @var RoleDecoratorInterface
     */
    private $roleDecorator;

    /**
     * A security context
     *
     * @var SecurityContextInterface
     */
    private $securityContext;

    /**
     * Constructor
     *
     * @param RoleProviderInterface    $roleProvider    A role provider
     * @param RoleDecoratorInterface   $roleDecorator   A role decorator
     * @param SecurityContextInterface $securityContext A security context
     */
    public function __construct(
        RoleProviderInterface $roleProvider,
        RoleDecoratorInterface $roleDecorator,
        SecurityContextInterface $securityContext
    ) {
        $this->roleProvider = $roleProvider;
        $this->roleDecorator = $roleDecorator;
        $this->securityContext = $securityContext;
    }

    /**
     *
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        if (false === $this->securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            throw new \RuntimeException('Cannot display role management when no user is authenticated');
        }

        $currentUser = $this->securityContext->getToken()->getUser();

        $choices = [];
        $readOnlyChoices = [];

        $reachableRoles = $this->roleProvider->getReachableRolesForRole($currentUser->getRoles());

        // we iterate over the roles that the current user can reach
        // based off their current role, this is because we can't let
        // a user grant roles that they don't have themselves
        foreach ($reachableRoles as $reachableRole) {
            $choices[$reachableRole->getRole()] = $this->roleDecorator->decorate($reachableRole);
        }

        // we also iterate over all of the application roles and add
        // a "ready_only_choices" entry for those that the user cannot
        // reach so that they appear on the form as readonly fields for
        // display purposes
        foreach ($this->roleProvider->getAllRoles() as $role) {
            $roleName = $role->getRole();
            if (!isset($choices[$roleName])) {
                $readOnlyChoices[$roleName] = $this->roleDecorator->decorate($role);
            }
        }

        $defaultOptions = [
            'choices' => $choices,
            'read_only_choices' => $readOnlyChoices,
            'expanded' => true,
            'multiple' => true
        ];

        $resolver->setDefaults($defaultOptions);
    }

    /**
     * {@inheritDoc}
     *
     * @return string
     */
    public function getParent()
    {
        return 'choice';
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'tickit_roles';
    }
}
