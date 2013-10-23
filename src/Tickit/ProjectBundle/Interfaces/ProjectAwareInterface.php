<?php

/*
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
 */

namespace Tickit\ProjectBundle\Interfaces;

use Tickit\ProjectBundle\Entity\Project;

/**
 * Interface for classes that are Project aware
 *
 * @package Tickit\ProjectBundle\Interfaces
 * @author  James Halsall <james.t.halsall@googlemail.com>
 * @see     Tickit\ProjectBundle\Entity\Project
 */
interface ProjectAwareInterface
{
    /**
     * Gets the project on this object
     *
     * @return Project
     */
    public function getProject();

    /**
     * Sets the project on this object
     *
     * @param Project $project The project to set
     *
     * @return mixed
     */
    public function setProject(Project $project);
}
