<?php

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
