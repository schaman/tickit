<?php

namespace Tickit\UserBundle\Event;

use Tickit\CoreBundle\Event\AbstractVetoableEvent;
use Tickit\CoreBundle\Event\Interfaces\EntityAwareEventInterface;
use Tickit\UserBundle\Entity\User;
use Tickit\UserBundle\Interfaces\UserAwareInterface;

/**
 * Event fired before a user is updated in the application
 *
 * @package Tickit\UserBundle\Event
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class BeforeUpdateEvent extends AbstractVetoableEvent implements UserAwareInterface, EntityAwareEventInterface
{
    /**
     * The user that is to be updated
     *
     * @var User
     */
    protected $user;

    /**
     * Constructor.
     *
     * @param User $user The user entity that is to be updated
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Gets the user on this event
     *
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Sets the user on this event
     *
     * @param User $user The user to set
     *
     * @return mixed
     */
    public function setUser(User $user)
    {
        $this->user = $user;
    }

    /**
     * Gets the entity associated with this event
     *
     * @return User
     */
    public function getEntity()
    {
        return $this->getUser();
    }

    /**
     * Sets the entity associated with this event
     *
     * @param object $entity The entity to attach to the event
     */
    public function setEntity($entity)
    {
        $this->setUser($entity);
    }
}
