<?php

namespace Tickit\Component\Entity\Repository;

use Tickit\Component\Model\User\User;

/**
 * User Notification repository interface.
 *
 * User Notification repositories are responsible for fetching user
 * notification objects from the data layer.
 *
 * @package Tickit\Component\Entity\Repository
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
interface UserNotificationRepositoryInterface
{
    /**
     * Finds unread user notifications
     *
     * @param User $user The user to find unread notifications for
     *
     * @return array
     */
    public function findUnreadForUser(User $user);
}
