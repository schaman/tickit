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

namespace Tickit\Component\Model\Project;

/**
 * The ProjectSettingValue entity represents a project's value against a specific ProjectSetting
 *
 * @package Tickit\Component\Model\Project
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class ProjectSettingValue
{
    /**
     * The unique identifier for this setting value
     *
     * @var integer
     */
    protected $id;

    /**
     * The project that this setting value is associated with
     *
     * @var Project
     */
    protected $project;

    /**
     * The setting that this value is for
     *
     * @var ProjectSetting
     */
    protected $setting;

    /**
     * The setting value
     *
     * @var string
     */
    protected $value;

    /**
     * Gets the associated setting object
     *
     * @return ProjectSetting
     */
    public function getSetting()
    {
        return $this->setting;
    }

    /**
     * Gets the attribute value
     *
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }
}
