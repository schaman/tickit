<?php

namespace Tickit\TeamBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use Tickit\TeamBundle\Entity\Team;
use Tickit\TeamBundle\Interfaces\TeamAwareInterface;

/**
 * Team deleted event.
 *
 * Dispatched after a team has been deleted from the entity manager
 *
 * @package Tickit\TeamBundle\Event
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class DeleteEvent extends Event implements TeamAwareInterface
{
    /**
     * The team that is to be deleted
     *
     * @var Team
     */
    protected $team;

    /**
     * Constructor.
     *
     * @param Team $team The team entity to be deleted
     */
    public function __construct(Team $team)
    {
        $this->team = $team;
    }

    /**
     * Gets the team on this event
     *
     * @return Team
     */
    public function getTeam()
    {
        return $this->team;
    }

    /**
     * Sets the team on this event
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