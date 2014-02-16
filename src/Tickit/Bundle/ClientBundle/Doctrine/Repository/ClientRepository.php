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

namespace Tickit\Bundle\ClientBundle\Doctrine\Repository;

use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Tickit\Bundle\PaginationBundle\Doctrine\Repository\PaginatedRepository;
use Tickit\Component\Filter\Collection\FilterCollection;
use Tickit\Component\Filter\Repository\FilterableRepositoryInterface;

/**
 * Client repository.
 *
 * Provides methods for fetching client data.
 *
 * @package Tickit\Bundle\ClientBundle\Doctrine\Repository
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class ClientRepository extends PaginatedRepository implements FilterableRepositoryInterface
{
    /**
     * Finds results based off a set of filters.
     *
     * @param FilterCollection $filters The filter collection
     * @param integer          $page    The page number of results to fetch
     *
     * @codeCoverageIgnore
     *
     * @return Paginator
     */
    public function findByFilters(FilterCollection $filters, $page = 1)
    {
        $query = $this->getFindByFiltersQueryBuilder($filters, $page);
        $paginator = new Paginator($query, false);

        return $paginator;
    }

    /**
     * Gets the query builder for finding a filtered set of Clients
     *
     * @param FilterCollection $filters The filter collection
     * @param integer          $page    The page number of results to fetch
     *
     * @return QueryBuilder
     */
    public function getFindByFiltersQueryBuilder(FilterCollection $filters, $page)
    {
        $queryBuilder = $this->getEntityManager()
                             ->createQueryBuilder()
                             ->select('c')
                             ->from('TickitClientBundle:Client', 'c');

        $this->setPageBoundsOnQuery($queryBuilder, $page);
        $filters->applyToQuery($queryBuilder);

        return $queryBuilder;
    }
}
