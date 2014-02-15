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

namespace Tickit\Component\Notification\Tests\Manager;

use Tickit\Component\Notification\Event\NotificationEvents;
use Tickit\Component\Notification\Event\UserNotificationEvent;
use Tickit\Component\Test\AbstractUnitTest;
use Tickit\Component\Notification\Model\UserNotification;
use Tickit\Component\Notification\Factory\NotificationFactory;
use Tickit\Component\Notification\Manager\NotificationManager;

/**
 * NotificationManager tests
 *
 * @package Tickit\Component\Notification\Tests\Manager
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class NotificationManagerTest extends AbstractUnitTest
{
    /**
     * The notification factory dependency
     *
     * @var NotificationFactory
     */
    private $factory;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $em;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $dispatcher;

    /**
     * Setup
     */
    protected function setUp()
    {
        $this->factory = new NotificationFactory();
        $this->em = $this->getMockEntityManager();
        $this->dispatcher = $this->getMockEventDispatcher();
    }
    
    /**
     * Tests the create() method
     */
    public function testCreateUserNotificationPersistsAndFlushesNotification()
    {
        $notification = new UserNotification();

        $this->em->expects($this->once())
                 ->method('persist')
                 ->with($notification);

        $this->em->expects($this->once())
                 ->method('flush');

        $expectedEvent = new UserNotificationEvent($notification);

        $this->dispatcher->expects($this->once())
                         ->method('dispatch')
                         ->with(NotificationEvents::NOTIFY_USER, $expectedEvent);

        $this->getManager()->createUserNotification($notification);
    }

    /**
     * Tests the getFactory() method
     */
    public function testGetFactoryReturnsCorrectInstance()
    {
        $this->assertSame($this->factory, $this->getManager()->getFactory());
    }

    private function getManager()
    {
        return new NotificationManager($this->em, $this->factory, $this->dispatcher);
    }
}
