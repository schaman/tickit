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

namespace Tickit\Bundle\PreferenceBundle\Doctrine\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Tickit\Component\Entity\Repository\PreferenceRepositoryInterface;
use Tickit\Component\Filter\Repository\FilterableRepositoryInterface;
use Tickit\Component\Filter\Collection\FilterCollection;

/**
 * Preference repository.
 *
 * Provides methods for fetching preference related data from the data layer.
 *
 * @package Tickit\Bundle\PreferenceBundle\Doctrine\Repository
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class PreferenceRepository extends EntityRepository implements PreferenceRepositoryInterface, FilterableRepositoryInterface
{
    /**
     * {@inheritDoc}
     *
     * @param integer[] $exclusions An array of Preference ids to ignore
     *
     * @codeCoverageIgnore
     *
     * @return array
     */
    public function findAllWithExclusionsIndexedBySystemName(array $exclusions = array())
    {
        return $this->getFindAllWithExclusionsIndexedBySystemNameQueryBuilder($exclusions)->getQuery()->execute();
    }

    /**
     * Gets a query builder that finds all preferences and returns them by system name.
     *
     * @param array $exclusions An array of excluded preference IDs not to include in the result (optional)
     *
     * @return QueryBuilder
     */
    public function getFindAllWithExclusionsIndexedBySystemNameQueryBuilder(array $exclusions = array())
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder();

        $queryBuilder = $queryBuilder->select('p')
                                     ->from('TickitPreferenceBundle:Preference', 'p', 'p.systemName');

        if (!empty($exclusions)) {
            $queryBuilder->where($queryBuilder->expr()->notIn('p.id', $exclusions));
        }

        return $queryBuilder;
    }

    /**
     * Finds results based off a set of filters.
     *
     * @param FilterCollection $filters The filter collection
     * @param integer          $page    The page number of the results to fetch (defaults to 1)
     *
     * @codeCoverageIgnore
     *
     * @return mixed
     */
    public function findByFilters(FilterCollection $filters, $page = 1)
    {
        return $this->getFindByFiltersQueryBuilder($filters)->getQuery()->execute();
    }

    /**
     * Gets a query builder that returns a filtered set of preferences
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
                             ->from('TickitPreferenceBundle:Preference', 'p');

        $filters->applyToQuery($queryBuilder);

        return $queryBuilder;
    }
}
