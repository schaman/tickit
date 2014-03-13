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

namespace Tickit\Bundle\IssueBundle\Doctrine\Repository;

use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Tickit\Bundle\PaginationBundle\Doctrine\Repository\PaginatedRepository;
use Tickit\Component\Entity\Repository\IssueRepositoryInterface;
use Tickit\Component\Filter\Collection\FilterCollection;
use Tickit\Component\Filter\Repository\FilterableRepositoryInterface;
use Tickit\Component\Model\Project\Project;

/**
 * Issue repository.
 *
 * @package Tickit\Bundle\Doctrine\Repository
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class IssueRepository extends PaginatedRepository implements IssueRepositoryInterface, FilterableRepositoryInterface
{
    /**
     * Finds results based off a set of filters.
     *
     * @param FilterCollection $filters The filter collection
     * @param integer          $page    The page number of the results to fetch (defaults to 1)
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
     * Gets the query builder for finding a filtered set of Issues
     *
     * @param FilterCollection $filters The filter collection
     * @param integer          $page    The page number of results to return
     *
     * @return QueryBuilder
     */
    public function getFindByFiltersQueryBuilder(FilterCollection $filters, $page)
    {
        $queryBuilder = $this->getEntityManager()
                             ->createQueryBuilder()
                             ->select('i, au')
                             ->from('TickitIssueBundle:Issue', 'i')
                             ->leftJoin('i.assignedTo', 'au');

        $this->setPageBoundsOnQuery($queryBuilder, $page);
        $filters->applyToQuery($queryBuilder);

        return $queryBuilder;
    }

    /**
     * Finds the last issue number used for the given project.
     *
     * By convention, this method should return 0 when no
     * issues were found for the project.
     *
     * @param Project $project The project to find the last issue number for.
     *
     * @coeCoverageIgnore
     *
     * @return integer
     */
    public function findLastIssueNumberForProject(Project $project)
    {
        $lastIssue = $this->getFindLastIssueQueryBuilder($project)
                          ->getQuery()
                          ->getOneOrNullResult(Query::HYDRATE_ARRAY);

        if (null === $lastIssue) {
            return 0;
        }

        return $lastIssue['number'];
    }

    /**
     * Gets a QueryBuilder fetching the last created Issue on a project.
     *
     * @param Project $project The project to find the last issue for
     *
     * @return QueryBuilder
     */
    public function getFindLastIssueQueryBuilder(Project $project)
    {
        return $this->getEntityManager()
                    ->createQueryBuilder()
                    ->select('i')
                    ->from('TickitIssueBundle:Issue', 'i')
                    ->where('i.project = :project')
                    ->setMaxResults(1)
                    ->setParameter('project', $project);
    }
}
