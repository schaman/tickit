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

use Tickit\Bundle\CoreBundle\Tests\AbstractOrmTest;
use Tickit\Bundle\ProjectBundle\Entity\Repository\ProjectRepository;

/**
 * ProjectRepository tests
 *
 * @package Tickit\Bundle\ProjectBundle\Tests\Entity\Repository
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
            'Tickit\\Bundle\\ProjectBundle\\Entity',
            array('TickitProjectBundle' => 'Tickit\\Bundle\\ProjectBundle\\Entity')
        );

        $this->repo = $em->getRepository('TickitProjectBundle:Project');
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
        $this->assertEquals($from[0]->getFrom(), 'TickitProjectBundle:Project');
    }
}
