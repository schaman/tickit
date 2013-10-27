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

namespace Tickit\NotificationBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Tickit\UserBundle\Entity\User;

/**
 * User notification repository.
 *
 * @package Tickit\NotificationBundle\Entity\Repository
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class UserNotificationRepository extends EntityRepository
{
    /**
     * Finds unread user notifications
     *
     * @param User $user The user to find unread notifications for
     *
     * @codeCoverageIgnore
     *
     * @return array
     */
    public function findUnreadForUser(User $user)
    {
        return $this->getFindUnreadForUserQueryBuilder($user)->getQuery()->execute();
    }

    /**
     * Gets a query builder that finds all unread notifications for a user
     *
     * @param User $user The user to find unread notifications for
     *
     * @return QueryBuilder
     */
    public function getFindUnreadForUserQueryBuilder(User $user)
    {
        $queryBuilder = $this->getEntityManager()
                   ->createQueryBuilder();

        $queryBuilder->select('n')
                     ->from('TickitNotificationBundle:UserNotification', 'n')
                     ->where('n.recipient = :user_id')
                     ->andWhere($queryBuilder->expr()->isNull('n.readAt'))
                     ->setParameter('user_id', $user->getId());

        return $queryBuilder;
    }
}
