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

namespace Tickit\Component\Notification\Tests\Provider;

use Tickit\Bundle\NotificationBundle\Entity\UserNotification;
use Tickit\Component\Notification\Provider\NotificationProvider;
use Tickit\Bundle\UserBundle\Entity\User;

/**
 * NotificationProviderTest tests
 *
 * @package Tickit\Component\Notification\Tests\Provider
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class NotificationProviderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * The provider under test
     *
     * @var NotificationProvider
     */
    private $provider;

    /**
     * Setup
     */
    protected function setUp()
    {
        $repo = $this->getMockBuilder('Tickit\Bundle\NotificationBundle\Entity\Repository\UserNotificationRepository')
                     ->disableOriginalConstructor()
                     ->getMock();

        $repo->expects($this->any())
             ->method('findUnreadForUser')
             ->will($this->returnValue(array(new UserNotification(), new UserNotification())));

        $this->provider = new NotificationProvider($repo, 20);
    }

    /**
     * Tests the getUserNotificationRepository() method
     */
    public function testGetUserNotificationRepositoryReturnsCorrectValue()
    {
        $repo = $this->provider->getUserNotificationRepository();

        $this->assertInstanceOf('Tickit\Bundle\NotificationBundle\Entity\Repository\UserNotificationRepository', $repo);
    }

    /**
     * Tests the findUnreadForUser() method
     */
    public function testFindUnreadForUserPassesCorrectValues()
    {
        $user = new User();
        $user->setUsername('username');

        $notifications = $this->provider->findUnreadForUser($user);

        $this->assertInternalType('array', $notifications);
        $this->assertCount(2, $notifications);
        $this->assertContainsOnlyInstancesOf('Tickit\Bundle\NotificationBundle\Entity\UserNotification', $notifications);
    }
}
