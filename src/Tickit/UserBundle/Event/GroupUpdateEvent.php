<?php

namespace Tickit\UserBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use Tickit\CoreBundle\Event\Interfaces\UpdateEventInterface;
use Tickit\UserBundle\Entity\Group;
use Tickit\UserBundle\Interfaces\GroupAwareInterface;

/**
 * Event dispatched when a group is updated
 *
 * @package Tickit\UserBundle\Event
 * @author  James Halsall <jhalsall@rippleffect.com>
 */
class GroupUpdateEvent extends Event implements UpdateEventInterface, GroupAwareInterface
{
    /**
     * The group that has been updated
     *
     * @var Group
     */
    protected $group;

    /**
     * The original group entity before updates
     *
     * @var Group
     */
    protected $originalGroup;

    /**
     * Constructor.
     *
     * @param Group $group         The group that has been updated
     * @param Group $originalGroup The original group entity before updates
     */
    public function __construct(Group $group, Group $originalGroup)
    {
        $this->setGroup($group);
        $this->setOriginalGroup($originalGroup);
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
     * Gets the original group entity on this object - before updates applied
     *
     * @return Group
     */
    public function getOriginalEntity()
    {
        return $this->originalGroup;
    }

    /**
     * Sets the original group on this object - before updates applied
     *
     * @param Group $originalGroup The group before updates were applied
     *
     * @return void
     */
    private function setOriginalGroup($originalGroup)
    {
        $this->originalGroup = $originalGroup;
    }
}
