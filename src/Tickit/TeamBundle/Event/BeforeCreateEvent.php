<?php

namespace Tickit\TeamBundle\Event;

use Tickit\CoreBundle\Event\AbstractVetoableEvent;
use Tickit\TeamBundle\Entity\Team;
use Tickit\TeamBundle\Interfaces\TeamAwareInterface;

/**
 * Event fired before a team is created in the application
 *
 * @package Tickit\TeamBundle\Event
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class BeforeCreateEvent extends AbstractVetoableEvent implements TeamAwareInterface
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
     * @param Team $team The team entity that is to be created
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
