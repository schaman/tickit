<?php

namespace Tickit\NotificationBundle\Factory;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\ORM\EntityManager;
use Tickit\NotificationBundle\Entity\GroupNotification;
use Tickit\NotificationBundle\Entity\UserNotification;
use Tickit\NotificationBundle\Model\NotificationMessageInterface;
use Tickit\UserBundle\Entity\Group;
use Tickit\UserBundle\Entity\User;

/**
 * Notification factory.
 *
 * Responsible for creating new notifications in the data layer, and triggering
 * a push to the client.
 *
 * @package Tickit\NotificationBundle\Factory
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class NotificationFactory
{
    /**
     * Creates a new user notification from a message object.
     *
     * @param NotificationMessageInterface $message The user notification message
     * @param User                         $user    The user that is being notified
     *
     * @throws \InvalidArgumentException If the notification message is empty
     *
     * @return UserNotification
     */
    public function notifyUser(NotificationMessageInterface $message, User $user)
    {
        $messageBody = $message->getMessage();
        if (empty($messageBody)) {
            throw new \InvalidArgumentException('You must provide a notification message');
        }

        $notification = new UserNotification();
        $notification->setRecipient($user)
                     ->setMessage($message->getMessage());

        return $notification;
    }

    /**
     * Creates a new group notification from a message object
     *
     * @param NotificationMessageInterface $message The group notification
     * @param Group                        $group   The group that is being notified
     *
     * @throws \InvalidArgumentException If the notification message is empty
     *
     * @return GroupNotification
     */
    public function notifyGroup(NotificationMessageInterface $message, Group $group)
    {
        $messageBody = $message->getMessage();
        if (empty($messageBody)) {
            throw new \InvalidArgumentException('You must provide a notification message');
        }

        $notification = new GroupNotification();
        $notification->setRecipient($group)
                     ->setMessage($message->getMessage());

        return $notification;
    }
}
