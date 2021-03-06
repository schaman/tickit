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

namespace Tickit\Bundle\NotificationBundle\Tests\Doctrine\Repository;

use Tickit\Bundle\CoreBundle\Tests\AbstractOrmTest;
use Tickit\Bundle\NotificationBundle\Doctrine\Repository\UserNotificationRepository;
use Tickit\Component\Model\User\User;

/**
 * UserNotificationRepository tests
 *
 * @package Tickit\Bundle\NotificationBundle\Tests\Doctrine\Repository
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class UserNotificationRepositoryTest extends AbstractOrmTest
{
    /**
     * The repo under test
     *
     * @var UserNotificationRepository
     */
    private $repo;

    /**
     * Setup
     */
    protected function setUp()
    {
        parent::setUp();

        $em = static::createTestEntityManager(
            array('TickitNotificationBundle' => 'Tickit\\Component\\Notification\\Model')
        );

        $this->repo = $em->getRepository('TickitNotificationBundle:UserNotification');
    }

    /**
     * Tests the getFindUnreadForUserQueryBuilder() method
     */
    public function testGetFindUnreadForUserQueryBuilderBuildsQuery()
    {
        $user = new User();
        $user->setId(5);

        $builder = $this->repo->getFindUnreadForUserQueryBuilder($user);

        $from = $builder->getDQLPart('from');
        $this->assertNotEmpty($from);
        $this->assertEquals('TickitNotificationBundle:UserNotification', $from[0]->getFrom());

        $where = $builder->getDQLPart('where');
        $whereParts = $where->getParts();
        $this->assertCount(2, $whereParts);

        $firstWhere = array_shift($whereParts);
        $secondWhere = array_shift($whereParts);

        $this->assertEquals('n.recipient = :user_id', $firstWhere);
        $this->assertEquals('n.readAt IS NULL', $secondWhere);
    }

    /**
     * Tests the getFindUnreadForUserQueryBuilder() method
     */
    public function testGetFindUnreadForUserReturnsNotificationsSinceDateTime()
    {
        $user = new User();
        $user->setId(10);
        $since = new \DateTime('-1 hour');

        $builder = $this->repo->getFindUnreadForUserQueryBuilder($user, $since);

        $where = $builder->getDQLPart('where');
        $whereParts = $where->getParts();
        $this->assertCount(3, $whereParts);

        $firstWhere = array_shift($whereParts);
        $secondWhere = array_shift($whereParts);
        $thirdWhere = array_shift($whereParts);

        $this->assertEquals('n.recipient = :user_id', $firstWhere);
        $this->assertEquals('n.readAt IS NULL', $secondWhere);
        $this->assertEquals('n.createdAt > :since', $thirdWhere);

        $this->assertEquals($since, $builder->getParameter('since')->getValue());
    }
}
