<?php

/*
 * Tickit, an source web based bug management tool.
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

namespace Tickit\CoreBundle\Tests\Filters\Collection\Builder;

use Symfony\Component\HttpFoundation\Request;
use Tickit\CoreBundle\Filters\AbstractFilter;
use Tickit\CoreBundle\Filters\Collection\Builder\FilterCollectionBuilder;

/**
 * FilterCollectionBuilder tests
 *
 * @package Tickit\CoreBundle\Tests\Filters\Collection\Builder
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class FilterCollectionBuilderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Tests the buildFromRequest() method
     *
     * @return void
     */
    public function testBuildFromRequestReturnsEmptyCollectionForNoFilters()
    {
        $request = Request::create('/', 'get', array());

        $builder = new FilterCollectionBuilder();
        $collection = $builder->buildFromRequest($request);

        $this->assertTrue($collection->isEmpty());
    }

    /**
     * Tests the buildFromRequest() method
     *
     * @return void
     */
    public function testBuildFromRequestReturnsValidCollection()
    {
        $filters = array(
            FilterCollectionBuilder::FILTER_ORDER_BY => array(
                'column' => 'ASC',
                'column2' => 'DESC'
            ),
            FilterCollectionBuilder::FILTER_EXACT_MATCH => array(
                'field1' => 'value',
                'field2' => 500
            ),
            FilterCollectionBuilder::FILTER_SEARCH => array(
                'field3' => 'search term',
                'field4' => 'another search term'
            )
        );

        $request = Request::create('/', 'get', $filters);
        $builder = new FilterCollectionBuilder();
        $collection = $builder->buildFromRequest($request);

        $this->assertEquals(6, $collection->count());

        /** @var AbstractFilter $filter */
        foreach ($collection as $filter) {
            $expectedValue = $filters[$filter->getType()][$filter->getKey()];
            $this->assertEquals($expectedValue, $filter->getValue());
        }
    }
}
