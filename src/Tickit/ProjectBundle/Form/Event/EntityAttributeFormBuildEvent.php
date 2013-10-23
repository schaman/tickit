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

namespace Tickit\ProjectBundle\Form\Event;

use Symfony\Component\EventDispatcher\Event;

/**
 * Event for the entity attribute form.
 *
 * This event is dispatched when the entity form is about to be built.
 *
 * The purpose of this event is to provide other bundles with the ability to make their
 * entities available for selection in the EntityAttributeFormType.
 *
 * @package Tickit\ProjectBundle\Form\Event
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class EntityAttributeFormBuildEvent extends Event
{
    /**
     * Current entity choices
     *
     * @var array
     */
    protected $entityChoices = array();

    /**
     * Adds an entity choice.
     *
     * @param string $className The class name of the entity
     * @param string $name      The display name for the entity
     */
    public function addEntityChoice($className, $name)
    {
        $this->entityChoices[$className] = $name;
    }

    /**
     * Gets current entity choices
     *
     * @return array
     */
    public function getEntityChoices()
    {
        return $this->entityChoices;
    }
}
