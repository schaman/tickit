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

namespace Tickit\Component\Notification\Tests\Factory;

use Tickit\Component\Notification\Factory\NotificationFactory;
use Tickit\Component\Model\User\User;
use Tickit\Component\Test\AbstractUnitTest;

/**
 * NotificationFactory tests
 *
 * @package Tickit\Component\Notification\Tests\Factory
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class NotificationFactoryTest extends AbstractUnitTest
{
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
        $this->em = $this->getMockEntityManager();
        $this->dispatcher = $this->getMockEventDispatcher();
    }

    /**
     * Tests the notifyUser() method
     *
     * @expectedException \InvalidArgumentException
     *
     * @return void
     */
    public function testNotifyUserThrowsExceptionForEmptyMessageObject()
    {
        $mockMessage = $this->getMock('Tickit\Component\Notification\Model\NotificationDataInterface');

        $this->getFactory()->notifyUser($mockMessage, new User());
    }

    /**
     * Tests the notifyUser() method
     *
     * @return void
     */
    public function testNotifyUserReturnsCorrectMessageObject()
    {
        $message = $this->getMock('Tickit\Component\Notification\Model\NotificationDataInterface');
        $message->expects($this->once())
                ->method('getMessage')
                ->will($this->returnValue(__FUNCTION__));

        $user = new User();
        $user->setForename('forename')
             ->getSurname('surname');

        $notification = $this->getFactory()->notifyUser($message, $user);

        $this->assertInstanceOf('Tickit\Component\Notification\Model\UserNotification', $notification);
        $this->assertEquals(__FUNCTION__, $notification->getMessage());
        $this->assertEquals($user->getForename(), $notification->getRecipient()->getForename());
        $this->assertEquals($user->getSurname(), $notification->getRecipient()->getSurname());
    }

    private function getFactory()
    {
        return new NotificationFactory($this->em, $this->dispatcher);
    }
}
