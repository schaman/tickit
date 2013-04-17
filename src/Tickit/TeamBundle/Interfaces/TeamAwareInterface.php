<?php

namespace Tickit\TeamBundle\Interfaces;

use Tickit\TeamBundle\Entity\Team;

/**
 * Interface for classes that are Team aware
 *
 * @package Namespace\Class
 * @author  James Halsall <jhalsall@rippleffect.com>
 * @see     Tickit\TeamBundle\Entity\Team
 */
interface TeamAwareInterface
{
    /**
     * Gets the team on this object
     *
     * @return Team
     */
    function getTeam();

    /**
     * Sets the team on this object
     *
     * @param Team $team The team to set
     *
     * @return mixed
     */
    function setTeam(Team $team);
}
