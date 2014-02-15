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

use Tickit\Component\Filter\Collection\FilterCollection;
use Tickit\Component\Test\AbstractUnitTest;

/**
 * FilterCollection tests
 *
 * @package Tickit\Component\Filter\Tests
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class FilterCollectionTest extends AbstractUnitTest
{
    /**
     * Tests the applyToQuery() method
     */
    public function testApplyToQueryDoesNotApplyFiltersForInvalidColumns()
    {
        $query = $this->getMockQueryBuilder();

        $searchFilter = $this->getMockBuilder('\Tickit\Component\Filter\SearchFilter')
                             ->disableOriginalConstructor()
                             ->getMock();

        $exactMatchFilter = $this->getMockBuilder('\Tickit\Component\Filter\ExactMatchFilter')
                                 ->disableOriginalConstructor()
                                 ->getMock();

        $searchFilter->expects($this->once())
                     ->method('applyToQuery')
                     ->with($query);

        $exactMatchFilter->expects($this->once())
                     ->method('applyToQuery')
                     ->with($query);

        $collection = new FilterCollection();
        $collection->add($searchFilter);
        $collection->add($exactMatchFilter);

        $collection->applyToQuery($query);
    }
}
