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

namespace Tickit\NotificationBundle\Tests\Factory;

use Tickit\NotificationBundle\Factory\NotificationFactory;
use Tickit\NotificationBundle\Tests\Mock\Model\MockNotification;
use Tickit\UserBundle\Entity\User;

/**
 * NotificationFactory tests
 *
 * @package Tickit\NotificationBundle\Tests\Factory
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class NotificationFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Tests the notifyUser() method
     *
     * @expectedException \InvalidArgumentException
     *
     * @return void
     */
    public function testNotifyUserThrowsExceptionForEmptyMessageObject()
    {
        $mockMessage = $this->getMock('Tickit\NotificationBundle\Model\NotificationDataInterface');

        $factory = new NotificationFactory();
        $factory->notifyUser($mockMessage, new User());
    }

    /**
     * Tests the notifyUser() method
     *
     * @return void
     */
    public function testNotifyUserReturnsCorrectMessageObject()
    {
        $message = new MockNotification();

        $user = new User();
        $user->setForename('forename')
             ->getSurname('surname');

        $factory = new NotificationFactory();
        $notification = $factory->notifyUser($message, $user);

        $this->assertInstanceOf('Tickit\NotificationBundle\Entity\UserNotification', $notification);
        $this->assertEquals($message->getMessage(), $notification->getMessage());
        $this->assertEquals($user->getForename(), $notification->getRecipient()->getForename());
        $this->assertEquals($user->getSurname(), $notification->getRecipient()->getSurname());
    }
}
