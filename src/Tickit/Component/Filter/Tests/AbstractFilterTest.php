<?php

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
 