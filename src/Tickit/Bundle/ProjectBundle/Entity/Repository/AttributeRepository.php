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

namespace Tickit\Bundle\ProjectBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Tickit\Bundle\CoreBundle\Entity\Repository\FilterableRepositoryInterface;
use Tickit\Component\Filter\Collection\FilterCollection;

/**
 * Attribute entity repository.
 *
 * Provides methods for fetching project attributes from the data layer
 *
 * @package Tickit\Bundle\ProjectBundle\Entity\Repository
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class AttributeRepository extends EntityRepository implements FilterableRepositoryInterface
{
    /**
     * Finds results based off a set of filters.
     *
     * @param FilterCollection $filters The filter collection
     *
     * @codeCoverageIgnore
     *
     * @return mixed
     */
    public function findByFilters(FilterCollection $filters)
    {
        return $this->getFindByFiltersQueryBuilder($filters)->getQuery()->execute();
    }

    /**
     * Gets the query builder for finding a filtered set of Projects
     *
     * @param FilterCollection $filters The filter collection
     *
     * @return QueryBuilder
     */
    public function getFindByFiltersQueryBuilder(FilterCollection $filters)
    {
        $queryBuilder = $this->getEntityManager()
                             ->createQueryBuilder()
                             ->select('a')
                             ->from('TickitProjectBundle:AbstractAttribute', 'a');

        $filters->applyToQuery($queryBuilder);

        return $queryBuilder;
    }

    /**
     * Returns a deep collection of all project attributes
     *
     * This method includes all associated meta objects related to the attributes.
     *
     * @codeCoverageIgnore
     *
     * @return mixed
     */
    public function findAllAttributes()
    {
        $choices = $this->getFindAllChoiceAttributesQueryBuilder()->getQuery()->execute();
        $others = $this->getFindAllNonChoiceAttributesQueryBuilder()->getQuery()->execute();

        return array_merge($others, $choices);
    }

    /**
     * Gets a query builder that finds all choice type attributes.
     *
     * Also fetches the available choices for those attributes.
     *
     * @return QueryBuilder
     */
    public function getFindAllChoiceAttributesQueryBuilder()
    {
        $queryBuilder = $this->getEntityManager()
                             ->createQueryBuilder()
                             ->select('c, ch')
                             ->from('TickitProjectBundle:ChoiceAttribute', 'c')
                             ->leftJoin('c.choices', 'ch');

        return $queryBuilder;
    }

    /**
     * Gets a query builder that fetches all non-choice type attributes
     *
     * @return QueryBuilder
     */
    public function getFindAllNonChoiceAttributesQueryBuilder()
    {
        $queryBuilder = $this->getEntityManager()
                             ->createQueryBuilder()
                             ->select('a')
                             ->from('TickitProjectBundle:AbstractAttribute', 'a')
                             ->where(
                                 'a INSTANCE OF TickitProjectBundle:LiteralAttribute OR
                                 a INSTANCE OF TickitProjectBundle:EntityAttribute'
                             );

        return $queryBuilder;
    }
}
