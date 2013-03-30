<?php

namespace Tickit\UserBundle\Interfaces;

use Tickit\UserBundle\Entity\User;

/**
 * Interface for classes that are Project aware
 *
 * @package Tickit\UserBundle\Interfaces
 * @author  James Halsall <jhalsall@rippleffect.com>
 * @see     Tickit\UserBundle\Entity\User
 */
interface UserAwareInterface
{
    /**
     * Gets the user on this object
     *
     * @return User
     */
    public function getUser();

    /**
     * Sets the user on this object
     *
     * @param User $user The user to set
     *
     * @return mixed
     */
    public function setUser(User $user);
}
