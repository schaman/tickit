<?php

/*
 * Tickit, an open source web based bug management tool.
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
 */

namespace Tickit\Component\Preference\Model;

/**
 * The Preference entity represents either a User preference of a System preference in the application
 *
 * @package Tickit\Component\Preference\Model
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class Preference
{
    const TYPE_USER = 'user';
    const TYPE_SYSTEM = 'system';

    /**
     * The unique identifier for this preference
     *
     * @var integer
     */
    protected $id;

    /**
     * The name of the preference
     *
     * @var string
     */
    protected $name;

    /**
     * The system friendly name of the preference
     *
     * @var string
     */
    protected $systemName;

    /**
     * The default value of the preference
     *
     * @var string
     */
    protected $defaultValue;

    /**
     * The preference type
     *
     * @var string
     */
    protected $type;

    /**
     * Gets the unique identifier for this preference
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets the unique identifier for the preference
     *
     * @param integer $id The new ID
     *
     * @return Preference
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Sets the name of this preference
     *
     * @param string $name The new preference name
     *
     * @return Preference
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Gets the preference name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets the system friendly name of the preference
     *
     * @param string $name The new system friendly name
     *
     * @return Preference
     */
    public function setSystemName($name)
    {
        $this->systemName = str_replace(' ', '', $name);

        return $this;
    }

    /**
     * Gets the system friendly name of the preference
     *
     * @return string
     */
    public function getSystemName()
    {
        return $this->systemName;
    }

    /**
     * Sets the default value for this preference
     *
     * @param string $defaultValue The new default value
     *
     * @return Preference
     */
    public function setDefaultValue($defaultValue)
    {
        $this->defaultValue = $defaultValue;

        return $this;
    }

    /**
     * Gets the default value for this preference
     *
     * @return string 
     */
    public function getDefaultValue()
    {
        return $this->defaultValue;
    }

    /**
     * Sets the type for this preference
     *
     * @param string $type The new preference type
     *
     * @throws \InvalidArgumentException If the given type is not valid
     *
     * @return Preference
     */
    public function setType($type)
    {
        if (!in_array($type, array(self::TYPE_USER, self::TYPE_SYSTEM))) {
            throw new \InvalidArgumentException('An invalid type has been specified in Preference entity');
        }
        $this->type = $type;

        return $this;
    }

    /**
     * Returns the type of the preference
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }
}
