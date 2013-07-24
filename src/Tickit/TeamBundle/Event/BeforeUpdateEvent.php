<?php

namespace Tickit\TeamBundle\Event;

use Tickit\CoreBundle\Event\AbstractVetoableEvent;
use Tickit\CoreBundle\Event\Interfaces\EntityAwareEventInterface;
use Tickit\TeamBundle\Entity\Team;
use Tickit\TeamBundle\Interfaces\TeamAwareInterface;

/**
 * Event fired before a team is updated in the application
 *
 * @package Tickit\TeamBundle\Event
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class BeforeUpdateEvent extends AbstractVetoableEvent implements TeamAwareInterface, EntityAwareEventInterface
{
    /**
     * The team that is to be updated
     *
     * @var Team
     */
    protected $team;

    /**
     * Constructor.
     *
     * @param Team $team The team entity that is to be updated
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

    /**
     * Gets the entity associated with this event
     *
     * @return Team
     */
    public function getEntity()
    {
        return $this->getTeam();
    }

    /**
     * Sets the entity associated with this event
     *
     * @param object $entity The entity to attach to the event
     */
    public function setEntity($entity)
    {
        $this->setTeam($entity);
    }
}
