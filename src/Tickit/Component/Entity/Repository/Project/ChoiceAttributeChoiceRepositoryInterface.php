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
use Doctrine\ORM\QueryBuilder;
use Tickit\Component\Model\Project\ChoiceAttribute;

/**
 * Choice Attribute Choice repository interface.
 *
 * These repositories are responsible for fetching ChoiceAttributeChoice objects
 * from the data layer.
 *
 * @package Tickit\Component\Entity\Repository\Project
 * @author  James Halsall <james.t.halsall@googlemail.com>
 * @see     Tickit\Component\Model\Project\ChoiceAttributeChoice
 */
interface ChoiceAttributeChoiceRepositoryInterface extends ObjectRepository
{
    /**
     * Finds choices available for a given attribute.
     *
     * @param ChoiceAttribute $attribute The attribute to find choices for
     *
     * @return array
     */
    public function findByAttribute(ChoiceAttribute $attribute);

    /**
     * Gets a QueryBuilder object that finds all choices for the given attribute.
     *
     * @param ChoiceAttribute $attribute The choice attribute to find choices for
     *
     * @return QueryBuilder
     */
    public function getFindAllForAttributeQueryBuilder(ChoiceAttribute $attribute);
}
