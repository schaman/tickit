<?php

namespace Tickit\CoreBundle\Tests\Filters;

use Doctrine\ORM\Query\Expr\Comparison;
use Doctrine\ORM\QueryBuilder;
use Tickit\CoreBundle\Filters\SearchFilter;

/**
 * SearchFilter tests
 *
 * @package Tickit\CoreBundle\Tests\Filters
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class SearchFilterTest extends AbstractFilterTestCase
{
    /**
     * Tests the applyToQuery() method
     *
     * @return void
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
     *
     * @return void
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
