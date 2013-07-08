<?php

namespace Tickit\CoreBundle\Tests\Filters;

use Doctrine\ORM\QueryBuilder;
use Tickit\CoreBundle\Filters\SearchFilter;
use Tickit\CoreBundle\Tests\AbstractFunctionalTest;

/**
 * SearchFilter tests
 *
 * @package Tickit\CoreBundle\Tests\Filters
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class SearchFilterTest extends AbstractFunctionalTest
{
    /**
     * Tests the applyToQuery() method
     *
     * @return void
     */
    public function testApplyToQueryDoesNotApplyFilterForInvalidKeyName()
    {
        $filter = new SearchFilter('invalid name', 'search value');
        $query = $this->getQueryBuilder();

        $filter->applyToQuery($query);
        $where = $query->getDQLPart('where');
        $this->assertNull($where);
    }

    /**
     * Tests the applyToQuery() method
     *
     * @return void
     */
    public function testApplyToQueryAppliesFilterForValidKeyName()
    {
        $filter = new SearchFilter('name', 'search value');

        $query = $this->getQueryBuilder()
                      ->select('p')
                      ->from('TickitPreferenceBundle:Preference', 'p');

        $filter->applyToQuery($query);

        $where = $query->getDQLPart('where');
        $this->assertInstanceOf('\Doctrine\ORM\Query\Expr\Andx', $where);
        /** @var \Doctrine\ORM\Query\Expr\Andx $where */
        $parts = $where->getParts();
        $this->assertCount(1, $parts);
        $part = array_shift($parts);
        /** @var \Doctrine\ORM\Query\Expr\Comparison $part */
        $this->assertEquals('p.name', $part->getLeftExpr());
        $this->assertEquals('LIKE', $part->getOperator());
        $this->assertEquals(':name', $part->getRightExpr());
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
