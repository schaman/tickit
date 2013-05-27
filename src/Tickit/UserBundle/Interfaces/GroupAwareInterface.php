<?php

namespace Tickit\UserBundle\Interfaces;

use Tickit\UserBundle\Entity\Group;

/**
 * Interface for classes that are Group aware
 *
 * @package Tickit\UserBundle\Interfaces
 * @author  James Halsall <jhalsall@rippleffect.com>
 * @see     Tickit\UserBundle\Entity\Group
 */
interface GroupAwareInterface
{
    /**
     * Gets the group on this object
     *
     * @return Group
     */
    public function getGroup();

    /**
     * Sets the group on this object
     *
     * @param Group $group The group to set
     *
     * @return mixed
     */
    public function setGroup(Group $group);
}
