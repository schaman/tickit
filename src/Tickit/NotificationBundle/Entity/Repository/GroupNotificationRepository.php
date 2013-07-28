<?php

namespace Tickit\NotificationBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Tickit\UserBundle\Entity\User;

/**
 * Group notification repository.
 *
 * @package Tickit\NotificationBundle\Entity\Repository
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class GroupNotificationRepository extends EntityRepository
{
    /**
     * Finds unread groups notifications for a user
     *
     * @param User $user The user to find unread group notifications for
     *
     * @return array
     */
    public function findUnreadForUser(User $user)
    {
        $qb = $this->getEntityManager()
                   ->createQueryBuilder();

        $qb->select('n')
           ->from('TickitNotificationBundle:GroupNotification', 'n')
           ->leftJoin('n.readStatuses', 'gnr')
           ->where('g.recipient = :group_id')
           ->andWhere($qb->expr()->isNull('gnr.readAt'))
           ->setParameter('group_id', $user->getGroup()->getId());

        return $qb->getQuery()->execute();
    }
}
