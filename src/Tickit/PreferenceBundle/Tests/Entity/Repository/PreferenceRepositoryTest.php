<?php

namespace Tickit\PreferenceBundle\Tests\Entity\Repository;

use Tickit\CoreBundle\Tests\AbstractOrmTest;
use Tickit\PreferenceBundle\Entity\Repository\PreferenceRepository;

/**
 * PreferenceRepository tests
 *
 * @package Tickit\PreferenceBundle\Tests\Entity\Repository
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class PreferenceRepositoryTest extends AbstractOrmTest
{
    /**
     * The repo under test
     *
     * @var PreferenceRepository
     */
    private $repo;

    /**
     * Setup
     */
    protected function setUp()
    {
        parent::setUp();

        $em = $this->getEntityManager(
            'Tickit\\PreferenceBundle\\Entity',
            array('TickitPreferenceBundle' => 'Tickit\\PreferenceBundle\\Entity')
        );

        $this->repo = $em->getRepository('TickitPreferenceBundle:Preference');
    }

    /**
     * Tests the getFindAllWithExclusionsIndexedBySystemNameQueryBuilder() method
     */
    public function testGetFindAllWithExclusionsIndexedBySystemNameQueryBuilderBuildsQueryWithExclusions()
    {
        $exclusions = array(1, 2, 3);

        $builder = $this->repo->getFindAllWithExclusionsIndexedBySystemNameQueryBuilder($exclusions);

        $from = $builder->getDQLPart('from');

        $this->assertNotEmpty($from);
        $this->assertEquals('TickitPreferenceBundle:Preference', $from[0]->getFrom());
        $this->assertEquals('p.systemName', $from[0]->getIndexBy());

        $where = $builder->getDQLPart('where');

        $this->assertNotEmpty($where);
        $this->assertNotEmpty($where->getParts());

        $whereClause = array_shift($where->getParts());
        $this->assertEquals('p.id NOT IN', $whereClause->getName());
        $this->assertEquals($exclusions, $whereClause->getArguments());
    }

    /**
     * Tests the getFindAllWithExclusionsIndexedBySystemNameQueryBuilder() method
     */
    public function testGetFindAllWithExclusionsIndexedBySystemNameQueryBuilderBuildsQueryWithoutExclusions()
    {
        $builder = $this->repo->getFindAllWithExclusionsIndexedBySystemNameQueryBuilder();

        $from = $builder->getDQLPart('from');

        $this->assertNotEmpty($from);
        $this->assertEquals('TickitPreferenceBundle:Preference', $from[0]->getFrom());
        $this->assertEquals('p.systemName', $from[0]->getIndexBy());

        $where = $builder->getDQLPart('where');

        $this->assertEmpty($where);
    }

    /**
     * Tests the getFindByFiltersQueryBuilder() method
     */
    public function testGetFindByFiltersQueryBuilderBuildsQuery()
    {
        $filters = $this->getMockBuilder('Tickit\CoreBundle\Filters\Collection\FilterCollection')
                        ->disableOriginalConstructor()
                        ->getMock();

        $filters->expects($this->once())
                ->method('applyToQuery');

        $builder = $this->repo->getFindByFiltersQueryBuilder($filters);

        $from = $builder->getDQLPart('from');
        $this->assertNotEmpty($from);
        $this->assertEquals($from[0]->getFrom(), 'TickitPreferenceBundle:Preference');
    }
}
