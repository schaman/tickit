<?php

namespace Tickit\TeamBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use Tickit\CoreBundle\Event\Interfaces\UpdateEventInterface;
use Tickit\TeamBundle\Entity\Team;
use Tickit\TeamBundle\Interfaces\TeamAwareInterface;

/**
 * Event dispatched when a team is updated
 *
 * Event name: "tickit_team.event.update"
 *
 * @package Tickit\TeamBundle\Event
 * @author  James Halsall <jhalsall@rippleffect.com>
 */
class UpdateEvent extends Event implements UpdateEventInterface, TeamAwareInterface
{
    /**
     * The team that has been updated
     *
     * @var Team
     */
    protected $team;

    /**
     * The original team entity before updates
     *
     * @var Team
     */
    protected $originalTeam;

    /**
     * Constructor.
     *
     * @param Team $team         The team that has been updated
     * @param Team $originalTeam The original team entity before updates
     */
    public function __construct(Team $team, Team $originalTeam)
    {
        $this->setTeam($team);
        $this->setOriginalTeam($originalTeam);
    }

    /**
     * Gets the team on this object
     *
     * @return Team
     */
    public function getTeam()
    {
        return $this->team;
    }

    /**
     * Sets the team on this object
     *
     * @param Team $team The team to set
     *
     * @return mixed
     */
    public function setTeam(Team $team)
    {
        $this->team = $team;
    }

    /**
     * Gets the original team entity on this object - before updates applied
     *
     * @return Team
     */
    public function getOriginalEntity()
    {
        return $this->originalTeam;
    }

    /**
     * Sets the original team on this object - before updates applied
     *
     * @param Team $originalTeam
     */
    private function setOriginalTeam($originalTeam)
    {
        $this->originalTeam = $originalTeam;
    }
}
