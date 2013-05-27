<?php

namespace Tickit\UserBundle\Event;

use Tickit\CoreBundle\Event\AbstractVetoableEvent;
use Tickit\CoreBundle\Event\Interfaces\EntityAwareEventInterface;
use Tickit\UserBundle\Entity\Group;
use Tickit\UserBundle\Interfaces\GroupAwareInterface;

/**
 * Event fired before a group is updated in the application
 *
 * @package Tickit\UserBundle\Event
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class GroupBeforeUpdateEvent extends AbstractVetoableEvent implements GroupAwareInterface, EntityAwareEventInterface
{
    /**
     * The group that is to be updated
     *
     * @var Group
     */
    protected $group;

    /**
     * Constructor.
     *
     * @param Group $group The group entity to be deleted
     */
    public function __construct(Group $group)
    {
        $this->group = $group;
    }

    /**
     * Gets the group on this event
     *
     * @return Group
     */
    public function getGroup()
    {
        return $this->group;
    }

    /**
     * Sets the group on this event
     *
     * @param Group $group The group to set
     *
     * @return mixed
     */
    public function setGroup(Group $group)
    {
        $this->group = $group;
    }

    /**
     * Gets the entity associated with this event
     *
     * @return Group
     */
    public function getEntity()
    {
        return $this->getGroup();
    }

    /**
     * Sets the entity associated with this event
     *
     * @param object $entity The entity to attach to the event
     */
    public function setEntity($entity)
    {
        $this->setGroup($entity);
    }
}
