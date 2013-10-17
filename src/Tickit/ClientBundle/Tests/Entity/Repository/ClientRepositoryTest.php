<?php

namespace Tickit\ClientBundle\Tests\Entity\Repository;

use Tickit\ClientBundle\Entity\Repository\ClientRepository;
use Tickit\CoreBundle\Tests\AbstractOrmTest;

/**
 * ClientRepository tests
 *
 * @package Tickit\ClientBundle\Tests\Entity\Repository
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class ClientRepositoryTest extends AbstractOrmTest
{
    /**
     * The repo under test
     *
     * @var ClientRepository
     */
    private $repo;

    /**
     * Setup
     */
    protected function setUp()
    {
        $em = $this->getEntityManager(
            'Tickit\\ClientBundle\\Entity',
            ['TickitClientBundle' => 'Tickit\\ClientBundle\\Entity']
        );

        $this->repo = $em->getRepository('TickitClientBundle:Client');
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
        $this->assertEquals($from[0]->getFrom(), 'TickitClientBundle:Client');
    }
}
