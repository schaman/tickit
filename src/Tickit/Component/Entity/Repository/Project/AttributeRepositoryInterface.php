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

namespace Tickit\Component\Entity\Repository\Project;

use Doctrine\Common\Persistence\ObjectRepository;

/**
 * Attribute repository interface.
 *
 * Attribute repositories are responsible for fetching AbstractAttribute objects
 * from the data layer.
 *
 * @package Tickit\Component\Entity\Repository\Project
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
interface AttributeRepositoryInterface extends ObjectRepository
{
    /**
     * Returns a deep collection of all attributes
     *
     * This method includes all associated meta objects related to the attributes.
     *
     * @return mixed
     */
    public function findAllAttributes();
}
