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
use Tickit\Component\Entity\Repository\ProjectRepositoryInterface;
use Tickit\Component\Filter\Repository\FilterableRepositoryInterface;
use Tickit\Component\Filter\Collection\FilterCollection;

/**
 * Project entity repository.
 *
 * Provides methods for retrieving Project related data from the data layer
 *
 * @author James Halsall <james.t.halsall@googlemail.com>
 */
class ProjectRepository extends EntityRepository implements ProjectRepositoryInterface, FilterableRepositoryInterface
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
            ->select('p')
            ->from('TickitProjectBundle:Project', 'p');

        $filters->applyToQuery($queryBuilder);

        return $queryBuilder;
    }
}
