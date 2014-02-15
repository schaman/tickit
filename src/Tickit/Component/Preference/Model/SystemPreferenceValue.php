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

/**
 * SystemPreferenceValue entity.
 *
 * Represents a value for a system preference in the application.
 *
 * @package Tickit\Component\Preference\Model
 * @author  James Halsall <james.t.halsall@googlemail.com>
 *
 */
class SystemPreferenceValue
{
    /**
     * The unique identifier
     *
     * @var integer
     */
    protected $id;

    /**
     * The preference that this value relates to
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
     * @return SystemPreferenceValue
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
     * @return SystemPreferenceValue
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
     * @return SystemPreferenceValue
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
     * Sets the value for this system preference
     *
     * @param mixed $value The new preference value
     *
     * @return SystemPreferenceValue
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Gets the value for this system preference
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }
}
