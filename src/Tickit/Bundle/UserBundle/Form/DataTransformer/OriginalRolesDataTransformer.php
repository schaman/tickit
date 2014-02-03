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

namespace Tickit\Bundle\UserBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\Security\Core\Role\RoleInterface;

/**
 * Restore roles data transformer.
 *
 * Responsible for ensuring
 *
 * @package Tickit\Bundle\UserBundle\Form\DataTransformer
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class OriginalRolesDataTransformer implements DataTransformerInterface
{
    /**
     * An array of original roles.
     *
     * These are the roles that were originally in state before
     * anything was edited.
     *
     * @var array
     */
    private $originalRoles = [];

    /**
     * An array of roles that are editable.
     *
     * @var array
     */
    private $editableRoles = [];

    /**
     * Transforms a value from the original representation to a transformed representation.
     *
     * By convention, transform() should return an empty string if NULL is
     * passed.
     *
     * @param mixed $value The value in the original representation
     *
     * @return mixed The value in the transformed representation
     */
    public function transform($value)
    {
        if (null === $value) {
            return '';
        }

        return $value;
    }

    /**
     * Transforms a value from the transformed representation to its original
     * representation.
     *
     * This method is called when {@link Form::submit()} is called to transform the requests tainted data
     * into an acceptable format for your data processing/model layer.
     *
     * This method must be able to deal with empty values. Usually this will
     * be an empty string, but depending on your implementation other empty
     * values are possible as well (such as empty strings). The reasoning behind
     * this is that value transformers must be chainable. If the
     * reverseTransform() method of the first value transformer outputs an
     * empty string, the second value transformer must be able to process that
     * value.
     *
     * By convention, reverseTransform() should return NULL if an empty string
     * is passed.
     *
     * @param mixed $value The value in the transformed representation
     *
     * @return mixed The value in the original representation
     *
     * @throws TransformationFailedException When the transformation fails.
     */
    public function reverseTransform($value)
    {
        if ('' === $value) {
            return null;
        }

        // we restore all roles that were originally granted on the edited user,
        // but are not currently editable in this context
        $rolesToRestore = array_diff($this->originalRoles, $this->editableRoles);

        foreach ($rolesToRestore as $role) {
            $value[] = $role;
        }

        return $value;
    }

    /**
     * Sets the original roles.
     *
     * The original roles will be added back when reverse transforming
     * submitted value data.
     *
     * These are the roles that were originally on the form before the data
     * was submitted.
     *
     * @param array|RoleInterface[] $roles The array of original roles
     */
    public function setOriginalRoles(array $roles)
    {
        $this->originalRoles = $this->normalizeRoles($roles);
    }

    /**
     * Sets editable roles.
     *
     * These are the roles that are mutable in the current context, and
     * are usually the roles that are granted to the user doing the editing.
     *
     * Only changes to roles in this array will be persisted, all other
     * role values will be taken from the $originalRoles values
     *
     * @param array|RoleInterface[] $roles The array of editable roles
     */
    public function setEditableRoles(array $roles)
    {
        $this->editableRoles = $this->normalizeRoles($roles);
    }

    /**
     * Flattens roles from RoleInterface to strings
     *
     * @param RoleInterface[] $roles The roles to flatten
     *
     * @return array
     */
    private function normalizeRoles(array $roles)
    {
        return array_map(
            function ($role) {
                if ($role instanceof RoleInterface) {
                    return $role->getRole();
                }

                return $role;
            },
            $roles
        );
    }
}