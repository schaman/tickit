<?php

namespace Tickit\PermissionBundle\Service;

use Symfony\Component\HttpFoundation\Session\Session;
use Tickit\UserBundle\Entity\User;

/**
 * Interface for permission service
 *
 * @package Tickit\PermissionBundle\Service
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
interface PermissionServiceInterface
{
    /**
     * Returns true if the session storage contains the given permission name, false otherwise
     *
     * @param string $permissionName The name of the permission
     *
     * @return bool
     */
    public function has($permissionName);

    /**
     * Writes an array of permission objects to the current session as a condensed array
     *
     * @param array $permissions An array of permission objects
     */
    public function writeToSession(array $permissions);

    /**
     * Loads new permissions for a given user from a data provider
     *
     * @param User $user The user to load permissions for
     *
     * @return mixed
     */
    public function loadFromProvider(User $user);

    /**
     * Returns the session instance associated with this service
     *
     * @return Session
     */
    public function getSession();
}
