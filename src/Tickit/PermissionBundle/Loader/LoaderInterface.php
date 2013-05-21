<?php

namespace Tickit\PermissionBundle\Loader;

use Tickit\UserBundle\Entity\User;

/**
 * Loader interface.
 *
 * Loaders are responsible for loading permission data into a context.
 *
 * @package Tickit\PermissionBundle\Loader
 * @author  James Halsall <jhalsall@rippleffect.com>
 */
interface LoaderInterface
{
    /**
     * Loads permissions into the current context for the given user.
     *
     * @param User $user The user to load permissions for
     *
     * @return mixed
     */
    public function loadForUser(User $user);
}
