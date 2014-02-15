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

namespace Tickit\Component\Notification\Tests\Provider;

use Tickit\Component\Notification\Model\UserNotification;
use Tickit\Component\Notification\Provider\NotificationProvider;
use Tickit\Component\Model\User\User;

/**
 * NotificationProviderTest tests
 *
 * @package Tickit\Component\Notification\Tests\Provider
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class NotificationProviderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $repo;

    /**
     * Setup
     */
    protected function setUp()
    {
        $this->repo = $this->getMock('\Tickit\Component\Entity\Repository\UserNotificationRepositoryInterface');
    }

    /**
     * Tests the getUserNotificationRepository() method
     */
    public function testGetUserNotificationRepositoryReturnsCorrectValue()
    {
        $repo = $this->getProvider()->getUserNotificationRepository();

        $this->assertSame($this->repo, $repo);
    }

    /**
     * Tests the findUnreadForUser() method
     */
    public function testFindUnreadForUserPassesCorrectValues()
    {
        $user = new User();
        $user->setUsername('username');

        $this->repo->expects($this->once())
                   ->method('findUnreadForUser')
                   ->with($user)
                   ->will($this->returnValue(array(new UserNotification(), new UserNotification())));

        $notifications = $this->getProvider()->findUnreadForUser($user);

        $this->assertInternalType('array', $notifications);
        $this->assertCount(2, $notifications);
        $this->assertContainsOnlyInstancesOf('Tickit\Component\Notification\Model\UserNotification', $notifications);
    }

    /**
     * Tests the findUnreadForUser() method
     */
    public function testFindUnreadForUserPassesOptionalSinceParameter()
    {
        $user = new User();
        $user->setUsername('username1');

        $since = new \DateTime('-1 day');

        $this->repo->expects($this->once())
                   ->method('findUnreadForUser')
                   ->with($user, $since)
                   ->will($this->returnValue(array(new UserNotification(), new UserNotification())));

        $this->getProvider()->findUnreadForUser($user, $since);
    }

    private function getProvider()
    {
        return new NotificationProvider($this->repo, 20);
    }
}
