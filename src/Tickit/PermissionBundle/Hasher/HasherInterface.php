<?php

namespace Tickit\PermissionBundle\Hasher;

/**
 * Hasher interface.
 *
 * Hashers are responsible for hashing permission data.
 *
 * @package Tickit\PermissionBundle\Hasher
 * @author  James Halsall <jhalsall@rippleffect.com>
 */
interface HasherInterface
{
    /**
     * Hashes an array of permissions into a single scalar
     *
     * @param array $permissions An array of permission data
     *
     * @return string
     */
    public function hash(array $permissions);
}
