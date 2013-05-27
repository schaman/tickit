<?php

namespace Tickit\UserBundle\Event;

use Tickit\CoreBundle\Event\AbstractVetoableEvent;
use Tickit\UserBundle\Entity\Group;
use Tickit\UserBundle\Interfaces\GroupAwareInterface;

/**
 * Event fired before a group is created in the application
 *
 * @package Tickit\UserBundle\Event
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class GroupBeforeCreateEvent extends AbstractVetoableEvent implements GroupAwareInterface
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
     * @param Group $group The group entity that is to be created
     */
    public function __construct(Group $group)
    {
        $this->group = $group;
    }

    /**
     * Gets the group on this object
     *
     * @return Group
     */
    public function getGroup()
    {
        return $this->group;
    }

    /**
     * Sets the group on this object
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
