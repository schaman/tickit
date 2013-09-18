<?php

namespace Tickit\CoreBundle\Tests\Filters;

use Doctrine\ORM\QueryBuilder;
use Tickit\CoreBundle\Filters\Collection\FilterCollection;
use Tickit\CoreBundle\Filters\ExactMatchFilter;
use Tickit\CoreBundle\Filters\SearchFilter;
use Tickit\CoreBundle\Tests\AbstractFunctionalTest;

/**
 * FilterCollection tests
 *
 * @package Tickit\CoreBundle\Tests\Filter
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class FilterCollectionTest extends AbstractFunctionalTest
{
    /**
     * Tests the applyToQuery() method
     *
     * @return void
     */
    public function testApplyToQueryDoesNotApplyFiltersWhenEmptyCollectionProvided()
    {
        $collection = new FilterCollection();
        $query = $this->getQueryBuilder();

        $collection->applyToQuery($query);
        $where = $query->getDQLPart('where');
        $this->assertNull($where);
    }

    /**
     * Tests the applyToQuery() method
     *
     * @return void
     */
    public function testApplyToQueryDoesNotApplyFiltersForInvalidColumns()
    {
        $collection = new FilterCollection();
        $collection->add(new SearchFilter('fake_column', 100));
        $collection->add(new ExactMatchFilter('name', 'preference name'));

        $query = $this->getQueryBuilder();
        $query->select('p')
              ->from('TickitPreferenceBundle:Preference', 'p');

        $collection->applyToQuery($query);

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
