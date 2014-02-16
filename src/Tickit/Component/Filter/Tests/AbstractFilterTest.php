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

use Tickit\Component\Filter\AbstractFilter;
use Tickit\Component\Test\AbstractUnitTest;

/**
 * AbstractFilter tests
 *
 * @package Tickit\Component\Filter\Tests
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class AbstractFilterTest extends AbstractUnitTest
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
     * Tests the getOption() method
     */
    public function testGetOptionReturnsFallbackValueForMissingOption()
    {
        $filter = $this->getMockAbstractFilter(['option' => 'value']);

        $value = $filter->getOption('invalid option', 'fallback');

        $this->assertEquals('fallback', $value);
    }

    /**
     * Tests the getOption() method
     */
    public function testGetOptionReturnsValidOptionValue()
    {
        $filter = $this->getMockAbstractFilter(['option' => 'value']);

        $value = $filter->getOption('option');

        $this->assertEquals('value', $value);
    }

    /**
     * Tests the getComparator() method
     */
    public function testGetComparatorReturnsDefault()
    {
        $filter = $this->getMockAbstractFilter();
        $method = $this->getNonAccessibleMethod(get_class($filter), 'getComparator');

        $comparator = $method->invoke($filter);

        $this->assertEquals(AbstractFilter::COMPARATOR_EQUAL, $comparator);
    }

    /**
     * Tests the getComparator() method
     */
    public function testGetComparatorReturnsSpecificValue()
    {
        $filter = $this->getMockAbstractFilter(['comparator' => AbstractFilter::COMPARATOR_GREATER_THAN_OR_EQUAL_TO]);
        $method = $this->getNonAccessibleMethod(get_class($filter), 'getComparator');

        $comparator = $method->invoke($filter);

        $this->assertEquals(AbstractFilter::COMPARATOR_GREATER_THAN_OR_EQUAL_TO, $comparator);
    }

    /**
     * Tests the getComparator() method
     */
    public function testGetComparatorReturnsDefaultForInvalidValue()
    {
        $filter = $this->getMockAbstractFilter(['comparator' => 'invalid comparator type']);
        $method = $this->getNonAccessibleMethod(get_class($filter), 'getComparator');

        $comparator = $method->invoke($filter);

        $this->assertEquals(AbstractFilter::COMPARATOR_EQUAL, $comparator);
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
            ),
            array(
                AbstractFilter::FILTER_CALLBACK,
                '\Tickit\Component\Filter\CallbackFilter'
            )
        );
    }

    private function getMockAbstractFilter(array $options = [])
    {
        return $this->getMockForAbstractClass(
            '\Tickit\Component\Filter\AbstractFilter',
            ['field-name', 'value', $options]
        );
    }
}
