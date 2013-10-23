<?php

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
