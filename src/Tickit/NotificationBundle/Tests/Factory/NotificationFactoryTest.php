<?php

namespace Tickit\NotificationBundle\Tests\Factory;
use Tickit\NotificationBundle\Factory\NotificationFactory;
use Tickit\UserBundle\Entity\Group;
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
        $mockMessage = $this->getMock('Tickit\NotificationBundle\Model\NotificationMessageInterface');

        $factory = new NotificationFactory();
        $factory->notifyUser($mockMessage, new User());
    }

    /**
     * Tests the notifyGroup() method
     *
     * @expectedException \InvalidArgumentException
     *
     * @return void
     */
    public function testNotifyGroupThrowsExceptionForEmptyMessageObject()
    {
        $mockMessage = $this->getMock('Tickit\NotificationBundle\Model\NotificationMessageInterface');

        $factory = new NotificationFactory();
        $factory->notifyGroup($mockMessage, new Group('test'));
    }
}
