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

namespace Tickit\Bundle\ProjectBundle\Entity;

/**
 * The ProjectSetting entity represents a specific setting that is customisable per project
 *
 * @package Tickit\Bundle\ProjectBundle\Entity
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class ProjectSetting
{
    /**
     * The unique identifier for the setting
     *
     * @var integer
     */
    protected $id;

    /**
     * The name of the setting
     *
     * @var string
     */
    protected $name;

    /**
     * The default value for the setting
     *
     * @var string
     */
    protected $defaultValue;

    /**
     * Gets the setting ID
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets the name of this setting
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Gets the name of this setting
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets the default value for this setting
     *
     * @param mixed $defaultValue
     */
    public function setDefaultValue($defaultValue)
    {
        $this->defaultValue = $defaultValue;
    }

    /**
     * Gets the default value for this setting
     *
     * @return mixed
     */
    public function getDefaultValue()
    {
        return $this->defaultValue;
    }
}
