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

use Tickit\Component\Filter\AbstractFilter;

/**
 * AbstractFilter tests
 *
 * @package Tickit\Component\Filter\Tests
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class AbstractFilterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Tests the factory() method
     *
     * @expectedException \InvalidArgumentException
     */
    public function testFactoryThrowsExceptionForInvalidFilterType()
    {
        AbstractFilter::factory('invalid type', 'key', 'value');
    }

    /**
     * Tests the factory() method
     *
     * @dataProvider getFilterTypeData
     */
    public function testFactoryReturnsCorrectFilterInstance($filterType, $expectedInstance)
    {
        $filter = AbstractFilter::factory($filterType, 'filter-key', 'filter value');

        $this->assertInstanceOf($expectedInstance, $filter);
        $this->assertEquals('filter-key', $filter->getKey());
        $this->assertEquals('filter value', $filter->getValue());
    }

    /**
     * Provides filter type test data
     *
     * @return array
     */
    public function getFilterTypeData()
    {
        return array(
            array(
                AbstractFilter::FILTER_EXACT_MATCH,
                '\Tickit\Component\Filter\ExactMatchFilter'
            ),
            array(
                AbstractFilter::FILTER_ORDER_BY,
                '\Tickit\Component\Filter\OrderByFilter'
            ),
            array(
                AbstractFilter::FILTER_SEARCH,
                '\Tickit\Component\Filter\SearchFilter'
            )
        );
    }
}
 