<?php

namespace Tickit\TeamBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use Tickit\TeamBundle\Entity\Team;
use Tickit\TeamBundle\Interfaces\TeamAwareInterface;

/**
 * Event dispatched when a team is created
 *
 * Event name: "tickit_team.event.create"
 *
 * @package Tickit\TeamBundle\Event
 * @author  James Halsall <jhalsall@rippleffect.com>
 */
class CreateEvent extends Event implements TeamAwareInterface
{
    /**
     * The team that has been created
     *
     * @var Team
     */
    protected $team;

    /**
     * Constructor.
     *
     * @param Team $team The team that is being created
     */
    public function __construct(Team $team)
    {
        $this->setTeam($team);
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
}
