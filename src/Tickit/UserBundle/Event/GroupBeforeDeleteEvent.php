<?php

namespace Tickit\UserBundle\Event;

use Tickit\CoreBundle\Event\AbstractVetoableEvent;
use Tickit\UserBundle\Entity\Group;
use Tickit\UserBundle\Interfaces\GroupAwareInterface;

/**
 * Event fired before a group is deleted from the application
 *
 * @package Tickit\UserBundle\Event
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class GroupBeforeDeleteEvent extends AbstractVetoableEvent implements GroupAwareInterface
{
    /**
     * The group that is to be deleted
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
}
