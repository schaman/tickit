<?php

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
