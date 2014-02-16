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

namespace Tickit\Component\Flash;

/**
 * Interface for flash message generators.
 *
 * Provides a way of encapsulating all flash message notifications across the
 * application.
 *
 * @package Tickit\Component\Flash
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
interface ProviderInterface
{
    /**
     * Adds a flash message to the flash bag for the creation of entities
     *
     * @param string $entityName The name of the entity that was created
     *
     * @return string
     */
    public function addEntityCreatedMessage($entityName);

    /**
     * Adds a flash message to the flash bag for the creation of entities
     *
     * @param string $entityName The name of the entity that was updated
     *
     * @return string
     */
    public function addEntityUpdatedMessage($entityName);

    /**
     * Adds a flash message to the flash bag for the creation of entities
     *
     * @param string $entityName The name of the entity that was deleted
     *
     * @return string
     */
    public function addEntityDeletedMessage($entityName);
}
