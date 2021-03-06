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

namespace Tickit\Component\Filter\Tests\Collection\Builder;

use Tickit\Component\Filter\AbstractFilter;
use Tickit\Component\Filter\Collection\Builder\FilterCollectionBuilder;
use Tickit\Component\Filter\Map\Definition\FilterDefinition;
use Tickit\Component\Filter\Collection\FilterCollection;

/**
 * FilterCollectionBuilder tests
 *
 * @package Tickit\Component\Filter\Tests\Collection\Builder
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class FilterCollectionBuilderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $filterMapper;

    /**
     * Setup
     */
    protected function setUp()
    {
        $this->filterMapper = $this->getMock('\Tickit\Component\Filter\Map\FilterMapperInterface', ['getFieldMap']);
    }

    /**
     * Tests the buildFromRequest() method
     *
     * @dataProvider getJoinTypeFixtures
     */
    public function testBuildFromRequestReturnsEmptyCollectionForNoFilters($joinType)
    {
        $this->filterMapper->expects($this->once())
                           ->method('getFieldMap')
                           ->will($this->returnValue(null));

        $builder = new FilterCollectionBuilder();
        if (null !== $joinType) {
            $collection = $builder->buildFromArray([], $this->filterMapper, $joinType);
        } else {
            $collection = $builder->buildFromArray([], $this->filterMapper);
        }

        $this->assertTrue($collection->isEmpty());
    }

    /**
     * Tests the buildFromRequest() method
     *
     * @dataProvider getJoinTypeFixtures
     */
    public function testBuildFromRequestIgnoresNonMappedFields($joinType, $expectedJoinType)
    {
        $filters = [
            'mapped_field' => 'search term',
            'unmapped_field' => 'search term'
        ];

        $fieldMap = ['mapped_field' => new FilterDefinition(AbstractFilter::FILTER_SEARCH)];

        $this->trainFilterMapperToReturnFieldMap($fieldMap);

        $builder = new FilterCollectionBuilder();
        if (null !== $joinType) {
            $collection = $builder->buildFromArray($filters, $this->filterMapper, $joinType);
        } else {
            $collection = $builder->buildFromArray($filters, $this->filterMapper);
        }


        $this->assertEquals(1, $collection->count());
        $mappedFilter = $collection->first();

        $this->assertEquals('mapped_field', $mappedFilter->getKey());
        $this->assertEquals($expectedJoinType, $mappedFilter->getOption('joinType'));
        $this->assertEquals(AbstractFilter::FILTER_SEARCH, $mappedFilter->getType());
    }

    /**
     * Tests the buildFromRequest() method
     *
     * @dataProvider getJoinTypeFixtures
     */
    public function testBuildFromRequestReturnsValidCollection($joinType, $expectedJoinType)
    {
        $filters = [
            'column' => 'ASC',
            'column2' => 'DESC',
            'field1' => 'value',
            'field2' => 500,
            'field3' => 'search term',
            'field4' => 'another search term'
        ];

        $fieldMap = [
            'column' => new FilterDefinition(AbstractFilter::FILTER_ORDER_BY),
            'column2' => new FilterDefinition(AbstractFilter::FILTER_ORDER_BY),
            'field1' => new FilterDefinition(AbstractFilter::FILTER_EXACT_MATCH),
            'field2' => new FilterDefinition(AbstractFilter::FILTER_EXACT_MATCH),
            'field3' => new FilterDefinition(AbstractFilter::FILTER_SEARCH),
            'field4' => new FilterDefinition(AbstractFilter::FILTER_SEARCH)
        ];

        $this->trainFilterMapperToReturnFieldMap($fieldMap);

        $builder = new FilterCollectionBuilder();
        if (null !== $joinType) {
            $collection = $builder->buildFromArray($filters, $this->filterMapper, $joinType);
        } else {
            $collection = $builder->buildFromArray($filters, $this->filterMapper);
        }

        $this->assertEquals(6, $collection->count());

        /** @var AbstractFilter $filter */
        foreach ($collection->toArray() as $filter) {
            $expectedType = $fieldMap[$filter->getKey()]->getType();
            $this->assertEquals($expectedType, $filter->getType());
            $this->assertEquals($filters[$filter->getKey()], $filter->getValue());
            $this->assertEquals($expectedJoinType, $filter->getOption('joinType'));
        }
    }

    /**
     * @return array
     */
    public function getJoinTypeFixtures()
    {
        return [
            [null, FilterCollection::JOIN_TYPE_AND],
            [FilterCollection::JOIN_TYPE_AND, FilterCollection::JOIN_TYPE_AND],
            [FilterCollection::JOIN_TYPE_OR, FilterCollection::JOIN_TYPE_OR]
        ];
    }

    private function trainFilterMapperToReturnFieldMap($fieldMap)
    {
        $this->filterMapper->expects($this->once())
                           ->method('getFieldMap')
                           ->will($this->returnValue($fieldMap));
    }
}
