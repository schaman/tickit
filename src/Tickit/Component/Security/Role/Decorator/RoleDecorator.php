<?php

namespace Tickit\Component\Security\Role\Decorator;

use Symfony\Component\Security\Core\Role\RoleInterface;

/**
 * Role decorator.
 *
 * Responsible for decorating system role names into more
 * friendly display names.
 *
 * @package Tickit\Component\Security\Role\Decorator
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class RoleDecorator
{
    /**
     * A map of role names to friendly names
     *
     * @var array
     */
    private $map = [
        'ROLE_USER'              => 'Tickit Login',
        'ROLE_ADMIN'             => 'Tickit Administrator',
        'ROLE_SUPER_ADMIN'       => 'Tickit Super Administrator',
        'ROLE_ALLOWED_TO_SWITCH' => 'Tickit Switch User'
    ];

    /**
     * Decorates a role into a more friendly string
     *
     * @param RoleInterface $role The role to decorate
     *
     * @return string
     */
    public function decorate(RoleInterface $role)
    {
        // todo
    }
}
