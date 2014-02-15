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

namespace Tickit\Component\Filter\Tests;

use Tickit\Component\Filter\OrderByFilter;

/**
 * OrderByFilter tests
 *
 * @package Tickit\Component\Filter\Tests
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class OrderByFilterTest extends AbstractFilterTestCase
{
    /**
     * Tests the applyToQuery() method
     *
     * @return void
     */
    public function testApplyToQueryDoesNotApplyFilterForInvalidKeyName()
    {
        $filter = new OrderByFilter('invalid name', OrderByFilter::DIR_DESC);

        $this->trainQueryToReturnRootEntities($this->query);
        $this->trainQueryToReturnEntityManager($this->query, $this->em);
        $this->trainEntityManagerToReturnClassMetaData($this->em);

        $this->query->expects($this->never())
                    ->method('getRootAliases');

        $this->query->expects($this->never())
                    ->method('addOrderBy');

        $filter->applyToQuery($this->query);
    }

    /**
     * Tests the applyToQuery() method
     *
     * @return void
     */
    public function testApplyToQueryAppliesFilterForValidKeyName()
    {
        $filter = new OrderByFilter('username', OrderByFilter::DIR_ASC);

        $this->trainQueryToReturnRootEntities($this->query);
        $this->trainQueryToReturnEntityManager($this->query, $this->em);
        $this->trainEntityManagerToReturnClassMetaData($this->em);

        $this->query->expects($this->once())
                    ->method('getRootAliases')
                    ->will($this->returnValue(array('u')));

        $this->query->expects($this->once())
                    ->method('addOrderBy')
                    ->with('u.username', OrderByFilter::DIR_ASC);

        $filter->applyToQuery($this->query);
    }

    /**
     * Tests the applyToQuery() method
     *
     * @return void
     */
    public function testApplyToQueryFallsBackToDefaultOrderForInvalidOrderType()
    {
        $filter = new OrderByFilter('username', 'crazy direction');

        $this->trainQueryToReturnRootEntities($this->query);
        $this->trainQueryToReturnEntityManager($this->query, $this->em);
        $this->trainEntityManagerToReturnClassMetaData($this->em);

        $this->query->expects($this->once())
                    ->method('getRootAliases')
                    ->will($this->returnValue(array('u')));

        $this->query->expects($this->once())
                    ->method('addOrderBy')
                    ->with('u.username', OrderByFilter::DIR_DESC);

        $filter->applyToQuery($this->query);
    }
}
