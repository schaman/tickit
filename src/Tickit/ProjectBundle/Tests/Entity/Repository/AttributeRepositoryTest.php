<?php

namespace Tickit\ProjectBundle\Tests\Entity\Repository;

use Doctrine\ORM\Query\Expr\Join;
use Tickit\CoreBundle\Tests\AbstractOrmTest;
use Tickit\ProjectBundle\Entity\Repository\AttributeRepository;

/**
 * AttributeRepository tests
 *
 * @package Tickit\ProjectBundle\Tests\Entity\Repository
 * @author  James Halsall <jhalsall@rippleffect.com>
 */
class AttributeRepositoryTest extends AbstractOrmTest
{
    /**
     * The repo under test
     *
     * @var AttributeRepository
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

        $this->repo = $em->getRepository('TickitProjectBundle:AbstractAttribute');
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
        $this->assertEquals($from[0]->getFrom(), 'TickitProjectBundle:AbstractAttribute');
    }

    /**
     * Tests the getFindAllChoiceAttributesQueryBuilder() method
     */
    public function testGetFindAllChoiceAttributesQueryBuilderBuildsQuery()
    {
        $builder = $this->repo->getFindAllChoiceAttributesQueryBuilder();

        $from = $builder->getDQLPart('from');
        $joins = $builder->getDQLPart('join');

        $this->assertNotEmpty($from);
        $this->assertEquals($from[0]->getFrom(), 'TickitProjectBundle:ChoiceAttribute');
        $this->assertCount(1, $joins);

        /** @var Join $join */
        $join = array_shift($joins['c']);
        $this->assertEquals('LEFT', $join->getJoinType());
        $this->assertEquals('c.choices', $join->getJoin());
    }

    /**
     * Tests the getFindAllNonChoiceAttributesQueryBuilder() method
     */
    public function testGetFindAllNonChoiceAttributesQueryBuilderBuildsQuery()
    {
        $builder = $this->repo->getFindAllNonChoiceAttributesQueryBuilder();

        $from = $builder->getDQLPart('from');
        $where = $builder->getDQLPart('where');

        $this->assertNotEmpty($from);
        $this->assertEquals($from[0]->getFrom(), 'TickitProjectBundle:AbstractAttribute');

        $this->assertInstanceOf('Doctrine\ORM\Query\Expr\Andx', $where);
        /** @var \Doctrine\ORM\Query\Expr\Andx $where */
        $part = array_shift($where->getParts());

        $pattern = '/a INSTANCE OF TickitProjectBundle:LiteralAttribute OR\s*a INSTANCE OF TickitProjectBundle:EntityAttribute/';
        $this->assertRegExp($pattern, $part);
    }
}
