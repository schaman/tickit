<?php

/*
 * Tickit, an open source web based bug management tool.
 *
 * Copyright (C) 2014  Tickit Project <http://tickit.io>
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
 */

namespace Tickit\Component\Preference\Model;

use Tickit\Component\Model\User\User;

/**
 * UserPreferenceValue entity.
 *
 * Represents a user's desired value for a preference in the application.
 *
 * @package Tickit\Component\Preference\Model
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class UserPreferenceValue
{
    /**
     * The unique identifier
     *
     * @var integer
     */
    protected $id;

    /**
     * The user that this preference value relates to
     *
     * @var User
     */
    protected $user;

    /**
     * The preference that this value is for
     *
     * @var Preference
     */
    protected $preference;

    /**
     * The value for the associated preference
     *
     * @var string
     */
    protected $value;

    /**
     * The date and time that this preference value was created
     *
     * @var \DateTime
     */
    protected $createdAt;

    /**
     * The date and time that this preference value was updated
     *
     * @var \DateTime
     */
    protected $updatedAt;


    /**
     * Sets the created time as an instance of DateTime
     *
     * @param \DateTime $createdAt The DateTime that this preference value was updated
     *
     * @return UserPreferenceValue
     */
    public function setCreatedAt(\DateTime $createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Gets the created time as an instance of DateTime
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Sets the Preference object
     *
     * @param Preference $preference The preference that this value relates to
     *
     * @return UserPreferenceValue
     */
    public function setPreference(Preference $preference)
    {
        $this->preference = $preference;

        return $this;
    }

    /**
     * Gets the Preference object
     *
     * @return Preference
     */
    public function getPreference()
    {
        return $this->preference;
    }

    /**
     * Sets the updated time as an instance of DateTime
     *
     * @param \DateTime $updatedAt The updated time for this preference value
     *
     * @return UserPreferenceValue
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * Gets the updated time as an instance of DateTime
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Sets the user that this value is for
     *
     * @param User $user The user object
     *
     * @return UserPreferenceValue
     */
    public function setUser(User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Gets the user that this value is for
     *
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Sets the value for this user preference
     *
     * @param mixed $value The new preference value
     *
     * @return UserPreferenceValue
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Gets the value for this user preference
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }
}
