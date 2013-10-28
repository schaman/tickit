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

namespace Tickit\Bundle\PreferenceBundle\Tests\Entity\Repository;

use Tickit\Bundle\CoreBundle\Tests\AbstractOrmTest;
use Tickit\Bundle\PreferenceBundle\Entity\Repository\PreferenceRepository;

/**
 * PreferenceRepository tests
 *
 * @package Tickit\Bundle\PreferenceBundle\Tests\Entity\Repository
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
            'Tickit\\Bundle\\PreferenceBundle\\Entity',
            array('TickitPreferenceBundle' => 'Tickit\\Bundle\\PreferenceBundle\\Entity')
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

        $whereParts = $where->getParts();
        $whereClause = array_shift($whereParts);
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
        $filters = $this->getMockBuilder('Tickit\Component\Filter\Collection\FilterCollection')
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
