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

use Doctrine\ORM\Mapping as ORM;

/**
 * The ProjectSettingValue entity represents a project's value against a specific ProjectSetting
 *
 * @package Tickit\Bundle\ProjectBundle\Entity
 * @author  James Halsall <james.t.halsall@googlemail.com>
 *
 * @ORM\Entity
 * @ORM\Table(name="project_setting_values")
 */
class ProjectSettingValue
{
    /**
     * The project that this setting value is associated with
     *
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Project")
     * @ORM\JoinColumn(name="project_id", referencedColumnName="id")
     */
    protected $project;

    /**
     * The setting that this value is for
     *
     * @ORM\Id
     * @ORM\OneToOne(targetEntity="ProjectSetting")
     * @ORM\JoinColumn(name="project_setting_id", referencedColumnName="id")
     */
    protected $setting;

    /**
     * The setting value
     *
     * @ORM\Column(type="string", length=120)
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
