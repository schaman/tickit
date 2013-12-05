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

namespace Tickit\Component\Filter\Tests;

use Doctrine\ORM\Query\Expr\Comparison;
use Tickit\Component\Filter\SearchFilter;

/**
 * SearchFilter tests
 *
 * @package Tickit\Component\Filter\Tests
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class SearchFilterTest extends AbstractFilterTestCase
{
    /**
     * Tests the applyToQuery() method
     */
    public function testApplyToQueryDoesNotApplyFilterForInvalidKeyName()
    {
        $filter = new SearchFilter('invalid name', 'search value');
        $em = $this->getMockEntityManager();
        $query = $this->getMockQueryBuilder();

        $this->trainQueryToReturnRootEntities($query);
        $this->trainQueryToReturnEntityManager($query, $em);
        $this->trainEntityManagerToReturnClassMetaData($em);

        $query->expects($this->never())
              ->method('getRootAliases');

        $query->expects($this->never())
              ->method('andWhere');

        $query->expects($this->never())
              ->method('setParameter');

        $filter->applyToQuery($query);
    }

    /**
     * Tests the applyToQuery() method
     */
    public function testApplyToQueryDoesNotApplyFilterWithEmptyValue()
    {
        $filter = new SearchFilter('username', '');
        $em = $this->getMockEntityManager();
        $query = $this->getMockQueryBuilder();

        $this->trainQueryToReturnRootEntities($query);
        $this->trainQueryToReturnEntityManager($query, $em);
        $this->trainEntityManagerToReturnClassMetaData($em);

        $query->expects($this->never())
              ->method('getRootAliases');

        $query->expects($this->never())
              ->method('andWhere');

        $query->expects($this->never())
              ->method('setParameter');

        $filter->applyToQuery($query);
    }

    /**
     * Tests the applyToQuery() method
     */
    public function testApplyToQueryAppliesFilterForValidKeyName()
    {
        $filter = new SearchFilter('username', 'search value');

        $em = $this->getMockEntityManager();
        $query = $this->getMockQueryBuilder();

        $this->trainQueryToReturnRootEntities($query);
        $this->trainQueryToReturnEntityManager($query, $em);
        $this->trainEntityManagerToReturnClassMetaData($em);

        $query->expects($this->once())
              ->method('getRootAliases')
              ->will($this->returnValue(['u']));

        $expression = new Comparison('u.username', 'LIKE', ':username');

        $expressionBuilder = $this->getMockBuilder('\Doctrine\ORM\Query\Expr')
                                  ->disableOriginalConstructor()
                                  ->getMock();

        $expressionBuilder->expects($this->once())
                          ->method('like')
                          ->with('u.username', ':username')
                          ->will($this->returnValue($expression));

        $query->expects($this->once())
              ->method('expr')
              ->will($this->returnValue($expressionBuilder));

        $query->expects($this->once())
              ->method('andWhere')
              ->with($expression)
              ->will($this->returnSelf());

        $query->expects($this->once())
              ->method('setParameter')
              ->with('username', '%search value%');

        $filter->applyToQuery($query);
    }
}
