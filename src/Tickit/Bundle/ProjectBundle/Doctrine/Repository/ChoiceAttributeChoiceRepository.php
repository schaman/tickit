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

namespace Tickit\Bundle\ProjectBundle\Doctrine\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Tickit\Component\Model\Project\ChoiceAttribute;

/**
 * ChoiceAttributeChoice repository.
 *
 * Provides functionality for fetching data for ChoiceAttributeChoice entities
 *
 * @package Tickit\Bundle\ProjectBundle\Doctrine\Repository
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class ChoiceAttributeChoiceRepository extends EntityRepository
{
    /**
     * Gets a QueryBuilder object that finds all choices for the given attribute.
     *
     * @param ChoiceAttribute $attribute The choice attribute to find choices for
     *
     * @return QueryBuilder
     */
    public function getFindAllForAttributeQueryBuilder(ChoiceAttribute $attribute)
    {
        $query = $this->getEntityManager()
                      ->createQueryBuilder()
                      ->select('c')
                      ->from('TickitProjectBundle:ChoiceAttributeChoice', 'c')
                      ->where('c.attribute = :attribute')
                      ->setParameter('attribute', $attribute->getId());

        return $query;
    }
}
