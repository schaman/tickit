<?php

namespace Tickit\CoreBundle\Tests\Filters;

use Doctrine\ORM\QueryBuilder;
use Tickit\CoreBundle\Filters\OrderByFilter;
use Tickit\CoreBundle\Tests\AbstractFunctionalTest;

/**
 * OrderByFilter tests
 *
 * @package Tickit\CoreBundle\Tests\Filters
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class OrderByFilterTest extends AbstractFunctionalTest
{
    /**
     * Tests the applyToQuery() method
     *
     * @return void
     */
    public function testApplyToQueryDoesNotApplyFilterForInvalidKeyName()
    {
        $filter = new OrderByFilter('invalid name', OrderByFilter::DIR_DESC);
        $query = $this->getQueryBuilder();

        $filter->applyToQuery($query);
        $orderBy = $query->getDQLPart('orderBy');
        $this->assertEmpty($orderBy);
    }

    /**
     * Tests the applyToQuery() method
     *
     * @return void
     */
    public function testApplyToQueryAppliesFilterForValidKeyName()
    {
        $filter = new OrderByFilter('name', OrderByFilter::DIR_ASC);

        $query = $this->getQueryBuilder()
                      ->select('p')
                      ->from('TickitPreferenceBundle:Preference', 'p');

        $filter->applyToQuery($query);

        $allOrderBy = $query->getDQLPart('orderBy');
        $this->assertInternalType('array', $allOrderBy);
        $order = array_shift($allOrderBy);
        $this->assertInstanceOf('\Doctrine\ORM\Query\Expr\OrderBy', $order);
        /** @var \Doctrine\ORM\Query\Expr\OrderBy $part */
        $this->assertCount(1, $order->getParts());
        $part = array_shift($order->getParts());
        $this->assertEquals('p.name ASC', $part);
    }

    /**
     * Tests the applyToQuery() method
     *
     * @return void
     */
    public function testApplyToQueryFallsBackToDefaultOrderForInvalidOrderType()
    {
        $filter = new OrderByFilter('name', 'crazy direction');

        $query = $this->getQueryBuilder()
                      ->select('p')
                      ->from('TickitPreferenceBundle:Preference', 'p');

        $filter->applyToQuery($query);

        $allOrderBy = $query->getDQLPart('orderBy');
        $this->assertInternalType('array', $allOrderBy);
        $order = array_shift($allOrderBy);
        $this->assertInstanceOf('\Doctrine\ORM\Query\Expr\OrderBy', $order);
        /** @var \Doctrine\ORM\Query\Expr\OrderBy $part */
        $this->assertCount(1, $order->getParts());
        $part = array_shift($order->getParts());
        $this->assertEquals('p.name DESC', $part);
    }

    /**
     * Gets a query builder
     *
     * @return QueryBuilder
     */
    private function getQueryBuilder()
    {
        $doctrine = $this->createClient()->getContainer()->get('doctrine');

        return $doctrine->getManager()->createQueryBuilder();
    }
}
