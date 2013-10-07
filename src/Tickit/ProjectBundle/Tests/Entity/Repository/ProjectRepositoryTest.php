<?php

namespace Tickit\ProjectBundle\Tests\Entity\Repository;

use Tickit\CoreBundle\Tests\AbstractOrmTest;
use Tickit\ProjectBundle\Entity\Repository\ProjectRepository;

/**
 * ProjectRepository tests
 *
 * @package Tickit\ProjectBundle\Tests\Entity\Repository
 * @author  James Halsall <jhalsall@rippleffect.com>
 */
class ProjectRepositoryTest extends AbstractOrmTest
{
    /**
     * The repo under test
     *
     * @var ProjectRepository
     */
    private $repo;

    /**
     * Setup
     */
    protected function setUp()
    {
        $em = $this->getEntityManager(
            'Tickit\\ProjectBundle\\Entity',
            array('TickitProjectBundle' => 'Tickit\\ProjectBundle\\Entity')
        );

        $this->repo = $em->getRepository('TickitProjectBundle:Project');
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
        $this->assertEquals($from[0]->getFrom(), 'TickitProjectBundle:Project');
    }
}