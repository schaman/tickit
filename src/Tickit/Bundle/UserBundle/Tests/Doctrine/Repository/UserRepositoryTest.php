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

namespace Tickit\Bundle\UserBundle\Tests\Doctrine\Repository;

use Tickit\Bundle\CoreBundle\Tests\AbstractOrmTest;
use Tickit\Bundle\UserBundle\Doctrine\Repository\UserRepository;
use Tickit\Component\Pagination\Resolver\PageResolver;

/**
 * UserRepository tests
 *
 * @package Tickit\Bundle\UserBundle\Tests\Doctrine\Repository
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
            array('TickitUserBundle' => 'Tickit\\Component\\Model\\User')
        );

        $this->repo = $em->getRepository('TickitUserBundle:User');
        $this->repo->setPageResolver(new PageResolver());
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
        $filters = $this->getMockBuilder('Tickit\Component\Filter\Collection\FilterCollection')
                        ->disableOriginalConstructor()
                        ->getMock();

        $filters->expects($this->once())
                ->method('applyToQuery');

        $builder = $this->repo->getFindByFiltersQueryBuilder($filters, 3);

        $from = $builder->getDQLPart('from');
        $this->assertNotEmpty($from);
        $this->assertEquals($from[0]->getFrom(), 'TickitUserBundle:User');

        $this->assertNotNull($builder->getFirstResult());
        $this->assertNotNull($builder->getMaxResults());
    }
}
