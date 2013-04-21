<?php

namespace Tickit\UserBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use Tickit\UserBundle\Entity\User;
use Tickit\UserBundle\Interfaces\UserAwareInterface;

/**
 * User deleted event.
 *
 * Dispatched after a user has been deleted from the entity manager
 *
 * @package Tickit\UserBundle\Event
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class DeleteEvent extends Event implements UserAwareInterface
{
    /**
     * The user that is to be deleted
     *
     * @var User
     */
    protected $user;

    /**
     * Constructor.
     *
     * @param User $user The user entity to be deleted
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
}
