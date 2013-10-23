<?php

namespace Tickit\NotificationBundle\Tests\Mock\Model;

use Tickit\NotificationBundle\Model\NotificationDataInterface;

/**
 * Mock notification message object.
 *
 * @package Tickit\NotificationBundle\Tests\Mock\Model
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class MockNotification implements NotificationDataInterface
{
    /**
     * Gets the notification message body.
     *
     * @return string
     */
    public function getMessage()
    {
        return 'mock notification message';
    }
}
