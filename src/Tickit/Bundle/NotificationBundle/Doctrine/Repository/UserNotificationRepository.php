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

namespace Tickit\Bundle\NotificationBundle\Doctrine\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Tickit\Component\Entity\Repository\UserNotificationRepositoryInterface;
use Tickit\Component\Model\User\User;

/**
 * User notification repository.
 *
 * @package Tickit\Bundle\NotificationBundle\Doctrine\Repository
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class UserNotificationRepository extends EntityRepository implements UserNotificationRepositoryInterface
{
    /**
     * {@inheritDoc}
     *
     * @param User $user The user to find unread notifications for
     * @param \DateTime $since The date and time to return notifications since (optional)
     *
     * @codeCoverageIgnore
     *
     * @return array
     */
    public function findUnreadForUser(User $user, \DateTime $since = null)
    {
        return $this->getFindUnreadForUserQueryBuilder($user, $since)->getQuery()->execute();
    }

    /**
     * Gets a query builder that finds all unread notifications for a user
     *
     * @param User      $user  The user to find unread notifications for
     * @param \DateTime $since The date and time to return notifications since (optional)
     *
     * @return QueryBuilder
     */
    public function getFindUnreadForUserQueryBuilder(User $user, \DateTime $since = null)
    {
        $queryBuilder = $this->getEntityManager()
                             ->createQueryBuilder();

        $queryBuilder->select('n')
                     ->from('TickitNotificationBundle:UserNotification', 'n')
                     ->where('n.recipient = :user_id')
                     ->andWhere($queryBuilder->expr()->isNull('n.readAt'))
                     ->setParameter('user_id', $user->getId());

        if (null !== $since) {
            $queryBuilder->andWhere('n.createdAt > :since')
                         ->setParameter('since', $since);
        }

        return $queryBuilder;
    }
}
