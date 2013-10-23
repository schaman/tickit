<?php

namespace Tickit\PreferenceBundle\Loader;

use Tickit\UserBundle\Entity\User;

/**
 * Loader interface.
 *
 * Loaders are responsible for loading preference data into a context.
 *
 * @package Tickit\PreferenceBundle\Loader
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
interface LoaderInterface
{
    /**
     * Loads preference into the current context for the given user.
     *
     * @param User $user The user to load preferences for
     *
     * @return mixed
     */
    public function loadForUser(User $user);
}
