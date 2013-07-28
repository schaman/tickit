<?php

namespace Tickit\NotificationBundle\Entity\Repository;
use Doctrine\ORM\EntityRepository;
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
     * @return array
     */
    public function findUnreadForUser(User $user)
    {
        $qb = $this->getEntityManager()
                   ->createQueryBuilder();

        $qb->select('n')
           ->from('TickitNotificationBundle:UserNotification', 'n')
           ->where('n.recipient = :user_id')
           ->where($qb->expr()->isNull('n.readAt'))
           ->setParameter('user_id', $user->getId());

        return $qb->getQuery()->execute();
    }
}
