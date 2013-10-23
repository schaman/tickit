<?php

/*
 * 
 * Tickit, an source web based bug management tool.
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
 * 
 */

namespace Tickit\UserBundle\Tests\Entity\Repository;

use Tickit\CoreBundle\Tests\AbstractOrmTest;
use Tickit\UserBundle\Entity\Repository\UserRepository;

/**
 * UserRepository tests
 *
 * @package Tickit\UserBundle\Tests\Entity\Repository
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class UserRepositoryTest extends AbstractOrmTest
{
    /**
     * The repo under test
     *
     * @var UserRepository
     */
    private $repo;

    /**
     * Setup
     */
    protected function setUp()
    {
        $em = $this->getEntityManager(
            'Tickit\\UserBundle\\Entity',
            array('TickitUserBundle' => 'Tickit\\UserBundle\\Entity')
        );

        $this->repo = $em->getRepository('TickitUserBundle:User');
    }
    
    /**
     * Tests the getFindByUsernameOrEmailQueryBuilder() method
     */
    public function testGetFindByUsernameOrEmailQueryBuilderBuildsQueryForUsernameSearch()
    {
        $builder = $this->repo->getFindByUsernameOrEmailQueryBuilder('username', UserRepository::COLUMN_USERNAME);

        $from = $builder->getDQLPart('from');

        $this->assertNotEmpty($from);
        $this->assertEquals('TickitUserBundle:User', $from[0]->getFrom());

        $where = $builder->getDQLPart('where');
        $whereParts = $where->getParts();

        $this->assertCount(1, $whereParts);
        $expression = array_shift($whereParts);
        $this->assertEquals('u.username = :username', $expression);

        $this->assertEquals('username', $builder->getParameter('username')->getValue());
    }

    /**
     * Tests the getFindByUsernameOrEmailQueryBuilder() method
     */
    public function testGetFindByUsernameOrEmailQueryBuilderBuildsQueryForEmailSearch()
    {
        $builder = $this->repo->getFindByUsernameOrEmailQueryBuilder('mail@domain.com', UserRepository::COLUMN_EMAIL);

        $from = $builder->getDQLPart('from');

        $this->assertNotEmpty($from);
        $this->assertEquals('TickitUserBundle:User', $from[0]->getFrom());

        $where = $builder->getDQLPart('where');
        $whereParts = $where->getParts();

        $this->assertCount(1, $whereParts);
        $expression = array_shift($whereParts);
        $this->assertEquals('u.email = :email', $expression);

        $this->assertEquals('mail@domain.com', $builder->getParameter('email')->getValue());
    }

    /**
     * Tests the getFindByIdQueryBuilder() method
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
        $this->assertEquals($from[0]->getFrom(), 'TickitUserBundle:User');
    }
}
