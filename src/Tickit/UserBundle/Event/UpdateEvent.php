<?php

namespace Tickit\UserBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use Tickit\CoreBundle\Event\Interfaces\UpdateEventInterface;
use Tickit\UserBundle\Entity\User;
use Tickit\UserBundle\Interfaces\UserAwareInterface;

/**
 * Event dispatched when a user is updated
 *
 * Event name: "tickit_user.event.update"
 *
 * @package Tickit\UserBundle\Event
 * @author  James Halsall <jhalsall@rippleffect.com>
 */
class UpdateEvent extends Event implements UpdateEventInterface, UserAwareInterface
{
    /**
     * The user that has been updated
     *
     * @var User
     */
    protected $user;

    /**
     * The original user entity before updates
     *
     * @var User
     */
    protected $originalUser;

    /**
     * Constructor.
     *
     * @param User $user         The user that has been updated
     * @param User $originalUser The original user entity before updates
     */
    public function __construct(User $user, User $originalUser)
    {
        $this->setUser($user);
        $this->setOriginalUser($originalUser);
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

    /**
     * Gets the original user entity on this object - before updates applied
     *
     * @return User
     */
    public function getOriginalEntity()
    {
        return $this->originalUser;
    }

    /**
     * Sets the original user on this object - before updates applied
     *
     * @param User $originalUser
     */
    private function setOriginalUser($originalUser)
    {
        $this->originalUser = $originalUser;
    }
}
