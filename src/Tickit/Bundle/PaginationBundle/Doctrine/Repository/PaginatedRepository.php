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

namespace Tickit\Bundle\PaginationBundle\Doctrine\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Tickit\Component\Pagination\Resolver\PageResolverInterface;

/**
 * Paginated repository.
 *
 * ORM repository that provides pagination support.
 *
 * @package Tickit\Bundle\PaginationBundle\Doctrine\Repository
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class PaginatedRepository extends EntityRepository
{
    /**
     * A page resolver
     *
     * @var PageResolverInterface
     */
    private $pageResolver;

    /**
     * Sets the page resolver
     *
     * @param PageResolverInterface $pageResolver A page resolver
     */
    public function setPageResolver(PageResolverInterface $pageResolver)
    {
        $this->pageResolver = $pageResolver;
    }

    /**
     * Gets the page resolver
     *
     * @throws \BadMethodCallException If the $pageResolver property is not set
     */
    public function getPageResolver()
    {
        if (null === $this->pageResolver) {
            throw new \BadMethodCallException(
                'The $pageResolver property has not been set, call setPageResolver() first. Have you configured ' .
                'the service in your container correctly?'
            );
        }

        return $this->pageResolver;
    }

    /**
     * Resolves the page bounds and sets them on the query
     *
     * @param QueryBuilder $queryBuilder The query builder object
     * @param integer      $page         The current page to set bounds for
     */
    public function setPageBoundsOnQuery(QueryBuilder $queryBuilder, $page)
    {
        $bounds = $this->getPageResolver()->resolve($page);

        $queryBuilder->setFirstResult($bounds->getOffset())
                     ->setMaxResults($bounds->getMaxResults());
    }
}
