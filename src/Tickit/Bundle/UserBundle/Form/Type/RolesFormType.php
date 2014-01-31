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
     * @var RoleInterface[]
     */
    private $roles;

    /**
     * A role decorator
     *
     * @var RoleDecoratorInterface
     */
    private $roleDecorator;

    /**
     * Constructor
     *
     * @param RoleProviderInterface  $roles         A role provider
     * @param RoleDecoratorInterface $roleDecorator A role decorator
     */
    public function __construct(RoleProviderInterface $roles, RoleDecoratorInterface $roleDecorator)
    {
        $this->roles = $roles->getAllRoles();
        $this->roleDecorator = $roleDecorator;
    }

    /**
     *
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $choices = [];

        foreach ($this->roles as $role) {
            $choices[$role->getRole()] = $this->roleDecorator->decorate($role);
        }

        $defaultOptions = [
            'choices' => $choices,
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
