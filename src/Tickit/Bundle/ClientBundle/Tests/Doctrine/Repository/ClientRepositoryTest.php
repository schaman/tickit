<?php

/*
 * Tickit, an open source web based bug management tool.
 *
 * Copyright (C) 2014  Tickit Project <http://tickit.io>
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

namespace Tickit\Bundle\ClientBundle\Tests\Doctrine\Repository;

use Tickit\Bundle\ClientBundle\Doctrine\Repository\ClientRepository;
use Tickit\Bundle\CoreBundle\Tests\AbstractOrmTest;
use Tickit\Component\Pagination\Resolver\PageResolver;

/**
 * ClientRepository tests
 *
 * @package Tickit\Bundle\ClientBundle\Tests\Doctrine\Repository
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
            ['TickitClientBundle' => 'Tickit\\Component\\Model\\Client']
        );

        $this->repo = $em->getRepository('TickitClientBundle:Client');
        $this->repo->setPageResolver(new PageResolver());
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

        $builder = $this->repo->getFindByFiltersQueryBuilder($filters, 3);

        $from = $builder->getDQLPart('from');
        $this->assertNotEmpty($from);
        $this->assertEquals($from[0]->getFrom(), 'TickitClientBundle:Client');

        $this->assertNotNull($builder->getFirstResult());
        $this->assertNotNull($builder->getMaxResults());
    }
}
