<?php

/*
 * 
 * Tickit, an source web based bug management tool.
 * 
 * Copyright (C) 2013  Tickit Project <http://tickit.io>
 * 
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 * 
 */

namespace Tickit\UserBundle\Entity;

use Doctrine\Common\Collections\Collection;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Tickit\UserBundle\Avatar\Entity\AvatarAwareInterface;

/**
 * The User entity represents a logged in user in the application
 *
 * @package Tickit\UserBundle\Entity
 * @author  James Halsall <james.t.halsall@googlemail.com>
 *
 * @ORM\Entity(repositoryClass="Tickit\UserBundle\Entity\Repository\UserRepository")
 * @ORM\Table(name="users")
 */
class User extends BaseUser implements AvatarAwareInterface
{
    const ROLE_ADMIN = 'ROLE_ADMIN';

    /**
     * The unique identifier for this user
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * The user's forename
     *
     * @ORM\Column(type="string", length=120)
     */
    protected $forename;

    /**
     * The user's surname
     *
     * @ORM\Column(type="string", length=120)
     */
    protected $surname;

    /**
     * The date and time this user was created
     *
     * @ORM\Column(type="datetime")
     * @Gedmo\Timestampable(on="create")
     */
    protected $created;

    /**
     * The date and time that this user was last updated
     *
     * @ORM\Column(type="datetime")
     * @Gedmo\Timestampable(on="update")
     */
    protected $updated;

    /**
     * @todo make this Many-to-Many
     *
     * @ORM\OneToMany(targetEntity="UserSession", mappedBy="user")
     */
    protected $sessions;

    /**
     * The date and time of this user's last activity
     *
     * @ORM\Column(name="last_activity", type="datetime", nullable=true)
     */
    protected $lastActivity;

    /**
     * Notifications for this user
     *
     * @var Collection
     * @ORM\OneToMany(targetEntity="Tickit\NotificationBundle\Entity\UserNotification", mappedBy="recipient")
     */
    protected $notifications;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->enabled = true;
        $this->sessions = new ArrayCollection();
        $this->notifications = new ArrayCollection();

        parent::__construct();
    }

    /**
     * Gets the ID for this user
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets the ID for this user
     *
     * @param integer $id The new user ID
     *
     * @return User
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Updates the user's forename
     *
     * @param string $forename The new forename value
     *
     * @return User
     */
    public function setForename($forename)
    {
        $this->forename = $forename;

        return $this;
    }

    /**
     * Gets the user's surname
     *
     * @return string
     */
    public function getSurname()
    {
        return $this->surname;
    }

    /**
     * Gets the user's forename
     *
     * @return string
     */
    public function getForename()
    {
        return $this->forename;
    }

    /**
     * Updates the user's surname
     *
     * @param string $surname The new surname value
     *
     * @return User
     */
    public function setSurname($surname)
    {
        $this->surname = $surname;

        return $this;
    }

    /**
     * Updates the last activity time
     *
     * @param \DateTime $lastActivity
     */
    public function setLastActivity($lastActivity)
    {
        $this->lastActivity = $lastActivity;
    }

    /**
     * Gets the last activity time as a DateTime object
     *
     * @return \DateTime
     */
    public function getLastActivity()
    {
        return $this->lastActivity;
    }

    /**
     * Adds a session object to this user's collection of sessions
     *
     * @param UserSession $session
     */
    public function addSession(UserSession $session)
    {
        $this->sessions[] = $session;
    }

    /**
     * Returns the current session token
     *
     * @return ArrayCollection
     */
    public function getSessions()
    {
        return $this->sessions;
    }

    /**
     * Gets the user's concatenated forename and surname
     *
     * @return string
     */
    public function getFullName()
    {
        return sprintf('%s %s', $this->forename, $this->surname);
    }

    /**
     * Gets the time this user was updated as a DateTime object
     *
     * @return \DateTime
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * Gets the created time as a DateTime object
     *
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Get the avatar identifier
     *
     * @return string
     */
    public function getAvatarIdentifier()
    {
        return $this->getEmail();
    }

    /**
     * Gets notifications for this user
     *
     * @return Collection
     */
    public function getNotifications()
    {
        return $this->notifications;
    }

    /**
     * Sets whether this user is an administrator or not
     *
     * @param boolean $value True if the user is an administrator, false otherwise
     *
     * @return User
     */
    public function setAdmin($value)
    {
        if (true === $value) {
            $this->addRole(static::ROLE_ADMIN);
        } else {
            $this->removeRole(static::ROLE_ADMIN);
        }

        return $this;
    }

    /**
     * Returns true if the user is an administrator, false otherwise
     *
     * @return boolean
     */
    public function isAdmin()
    {
        return $this->hasRole(static::ROLE_ADMIN);
    }
}
