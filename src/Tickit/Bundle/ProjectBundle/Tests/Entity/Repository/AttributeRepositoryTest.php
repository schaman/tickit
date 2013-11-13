<?php

/*
 * Tickit, an open source web based bug management tool.
 * 
 * Copyright (C) 2013  Tickit Project <http://tickit.io>
 * 
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Tickit\Bundle\ProjectBundle\Tests\Entity\Repository;

use Doctrine\ORM\Query\Expr\Join;
use Tickit\Bundle\CoreBundle\Tests\AbstractOrmTest;
use Tickit\Bundle\ProjectBundle\Entity\Repository\AttributeRepository;

/**
 * AttributeRepository tests
 *
 * @package Tickit\Bundle\ProjectBundle\Tests\Entity\Repository
 * @author  James Halsall <james.t.halsall@googlemail.com>
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
            array('TickitProjectBundle' => 'Tickit\\Bundle\\ProjectBundle\\Entity')
        );

        $this->repo = $em->getRepository('TickitProjectBundle:AbstractAttribute');
    }

    /**
     * Tests the getFindByFiltersQueryBuilder() method
     */
    public function testGetFindByFiltersQueryBuilderBuildsQuery()
    {
        $filters = $this->getMockBuilder('Tickit\Component\Filter\Collection\FilterCollection')
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
        $whereParts = $where->getParts();
        $part = array_shift($whereParts);

        $pattern = '/a INSTANCE OF TickitProjectBundle:LiteralAttribute OR\s*a INSTANCE OF TickitProjectBundle:EntityAttribute/';
        $this->assertRegExp($pattern, $part);
    }
}
