<?php

namespace Tickit\CoreBundle\Tests\Filters;

use Doctrine\ORM\QueryBuilder;
use Tickit\CoreBundle\Filters\ExactMatchFilter;
use Tickit\CoreBundle\Tests\AbstractFunctionalTest;

/**
 * ExactMatchFilter tests
 *
 * @package Tickit\CoreBundle\Tests\Filters
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class ExactMatchFilterTest extends AbstractFunctionalTest
{
    /**
     * Tests the applyToQuery() method
     *
     * @return void
     */
    public function testApplyToQueryDoesNotApplyFilterForInvalidKeyName()
    {
        $filter = new ExactMatchFilter('invalid name', 'exact value');
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
        $filter = new ExactMatchFilter('name', 'exact value');

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
        $this->assertEquals('p.name = :name', $part);
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
