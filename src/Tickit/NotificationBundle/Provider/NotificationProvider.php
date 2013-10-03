<?php

namespace Tickit\NotificationBundle\Provider;

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
     * The user notification repository
     *
     * @var UserNotificationRepository
     */
    protected $userNotificationRepo;

    /**
     * The maximum number of notification messages to return.
     *
     * @var integer
     */
    protected $messageLimit;

    /**
     * Constructor.
     *
     * @param UserNotificationRepository $userNotificationRepo The user notification repository
     * @param integer                    $messageLimit         The maximum number of notification messages to return
     */
    public function __construct(UserNotificationRepository $userNotificationRepo, $messageLimit)
    {
        $this->userNotificationRepo = $userNotificationRepo;
        $this->messageLimit = $messageLimit;
    }

    /**
     * Finds unread notifications for a given user
     *
     * @param User $user The user to find notifications for
     *
     * @return array
     */
    public function findUnreadForUser(User $user)
    {
        $userNotifications = $this->getUserNotificationRepository()
                                  ->findUnreadForUser($user);

        return $userNotifications;
    }

    /**
     * Gets the user notification repository
     *
     * @return UserNotificationRepository
     */
    public function getUserNotificationRepository()
    {
        return $this->userNotificationRepo;
    }
}
