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

namespace Tickit\Bundle\CoreBundle\Form\Type\Picker;

/**
 * Entity converter interface.
 *
 * Entity converters are responsible for converting an entity instance or an
 * entity identifier into a description scalar value that is used in a form's view.
 *
 * @package Tickit\Bundle\CoreBundle\Form\Type
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
interface EntityConverterInterface
{
    /**
     * Converts an entity or entity representation into a descriptive scalar representation
     * for form use.
     *
     * The method should throw an EntityNotFoundException in the event that an entity is invalid
     * or cannot be found.
     *
     * @param mixed $entity The entity to convert
     *
     * @throws \Doctrine\ORM\EntityNotFoundException
     *
     * @return mixed
     */
    public function convert($entity);
}
