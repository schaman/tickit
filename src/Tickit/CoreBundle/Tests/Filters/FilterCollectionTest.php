<?php

namespace Tickit\CoreBundle\Tests\Filters;

use Doctrine\ORM\QueryBuilder;
use Tickit\CoreBundle\Filters\Collection\FilterCollection;
use Tickit\CoreBundle\Tests\AbstractUnitTest;

/**
 * FilterCollection tests
 *
 * @package Tickit\CoreBundle\Tests\Filter
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class FilterCollectionTest extends AbstractUnitTest
{
    /**
     * Tests the applyToQuery() method
     *
     * @return void
     */
    public function testApplyToQueryDoesNotApplyFiltersForInvalidColumns()
    {
        $query = $this->getMockQueryBuilder();

        $searchFilter = $this->getMockBuilder('\Tickit\CoreBundle\Filters\SearchFilter')
                             ->disableOriginalConstructor()
                             ->getMock();

        $exactMatchFilter = $this->getMockBuilder('\Tickit\CoreBundle\Filters\ExactMatchFilter')
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
