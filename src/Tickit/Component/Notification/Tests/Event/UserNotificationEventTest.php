<?php

namespace Tickit\Component\Notification\Tests\Event;

use Tickit\Component\Model\User\User;
use Tickit\Component\Notification\Event\UserNotificationEvent;

/**
 * UserNotificationEvent tests
 *
 * @package Tickit\Component\Notification\Tests\Event
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class UserNotificationEventTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $notification;

    /**
     * Setup
     */
    protected function setUp()
    {
        $this->notification = $this->getMock('\Tickit\Component\Notification\Model\UserNotification');
    }

    /**
     * Tests the getUser() method
     */
    public function testGetUserReturnsRecipientFromNotification()
    {
        $user = new User();

        $this->notification->expects($this->once())
                           ->method('getRecipient')
                           ->will($this->returnValue($user));

        $this->assertSame($user, $this->getEvent()->getUser());
    }

    private function getEvent()
    {
        return new UserNotificationEvent($this->notification);
    }
}
