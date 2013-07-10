<?php

namespace Tickit\UserBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use Tickit\UserBundle\Entity\User;
use Tickit\UserBundle\Interfaces\UserAwareInterface;

/**
 * Event dispatched when a user is created
 *
 * Event name: "tickit_user.event.create"
 *
 * @package Tickit\UserBundle\Event
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class CreateEvent extends Event implements UserAwareInterface
{
    /**
     * The user that has been created
     *
     * @var User
     */
    protected $user;

    /**
     * Constructor.
     *
     * @param User $user The user that is being created
     */
    public function __construct(User $user)
    {
        $this->setUser($user);
    }

    /**
     * Gets the user on this object
     *
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Sets the user on this object
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
