<?php

namespace Tickit\NotificationBundle\Provider;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Tickit\NotificationBundle\Entity\Repository\UserNotificationRepository;
use Tickit\UserBundle\Entity\User;

/**
 * Notification provider.
 *
 * Responsible for providing notification data in the application.
 *
 * @package Tickit\NotificationBundle\Provider
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class NotificationProvider
{
    /**
     * The doctrine registry
     *
     * @var Registry
     */
    protected $doctrine;

    /**
     * The maximum number of notification messages to return.
     *
     * @var integer
     */
    protected $messageLimit;

    /**
     * Constructor.
     *
     * @param Registry $doctrine     The doctrine registry
     * @param integer  $messageLimit The maximum number of notification messages to return
     */
    public function __construct(Registry $doctrine, $messageLimit)
    {
        $this->doctrine = $doctrine;
        $this->messageLimit = $messageLimit;
    }

    /**
     * Finds unread notifications for a given user
     *
     * @param User $user The user to find notifications for
     *
     * @return void
     */
    public function findUnreadForUser(User $user)
    {
        return $this->getUserNotificationRepository()->findUnreadForUser($user);
    }

    /**
     * Gets the user notification repository
     *
     * @return UserNotificationRepository
     */
    public function getUserNotificationRepository()
    {
        return $this->doctrine->getRepository('TickitNotificationBundle:UserNotification');
    }
}
