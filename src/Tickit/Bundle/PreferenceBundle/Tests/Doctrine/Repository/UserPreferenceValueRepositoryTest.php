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

namespace Tickit\Bundle\PreferenceBundle\Tests\Doctrine\Repository;

use Tickit\Bundle\CoreBundle\Tests\AbstractOrmTest;
use Tickit\Bundle\PreferenceBundle\Doctrine\Repository\UserPreferenceValueRepository;
use Tickit\Bundle\UserBundle\Entity\User;

/**
 * UserPreferenceValueRepository tests
 *
 * @package Tickit\Bundle\PreferenceBundle\Tests\Doctrine\Repository
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class UserPreferenceValueRepositoryTest extends AbstractOrmTest
{
    /**
     * The repo under test
     *
     * @var UserPreferenceValueRepository
     */
    private $repo;

    /**
     * Setup
     */
    protected function setUp()
    {
        parent::setUp();

        $em = $this->getEntityManager(
            array('TickitPreferenceBundle' => 'Tickit\\Bundle\\PreferenceBundle\\Entity')
        );

        $this->repo = $em->getRepository('TickitPreferenceBundle:UserPreferenceValue');
    }

    /**
     * Tests the findAllForUserQueryBuilder() method
     */
    public function testGetFindAllForUserQueryBuilderBuildsQuery()
    {
        $user = new User();
        $user->setId(1);

        $builder = $this->repo->getFindAllForUserQueryBuilder($user);

        $from = $builder->getDQLPart('from');
        $this->assertNotEmpty($from);
        $this->assertEquals('TickitPreferenceBundle:UserPreferenceValue', $from[0]->getFrom());

        $joins = $builder->getDQLPart('join');
        $join = array_shift($joins['upv']);
        $this->assertEquals('INNER', $join->getJoinType());
        $this->assertEquals('upv.preference', $join->getJoin());

        $where = $builder->getDQLPart('where');
        $whereParts = $where->getParts();

        $this->assertCount(1, $whereParts);
        $expression = array_shift($whereParts);
        $this->assertEquals('upv.user = :user_id', $expression);

        $this->assertEquals(1, $builder->getParameter('user_id')->getValue());
    }
}
