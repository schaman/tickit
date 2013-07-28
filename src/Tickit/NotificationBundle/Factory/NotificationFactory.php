<?php

namespace Tickit\NotificationBundle\Factory;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\ORM\EntityManager;
use Tickit\NotificationBundle\Entity\GroupNotification;
use Tickit\NotificationBundle\Entity\UserNotification;

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
     * The entity manager
     *
     * @var EntityManager
     */
    protected $em;

    /**
     * Constructor.
     *
     * @param Registry $doctrine The doctrine registry
     */
    public function __construct(Registry $doctrine)
    {
        $this->em = $doctrine->getManager();
    }

    /**
     * Notifies a user of a new notification.
     *
     * @param UserNotification $notification The user notification
     *
     * @return void
     */
    public function notifyUser(UserNotification $notification)
    {
        $this->em->persist($notification);
        $this->em->flush();

        // TODO: push to the client ??
    }

    /**
     * Notifies a group of a new notification.
     *
     * @param GroupNotification $notification The group notification
     *
     * @return void
     */
    public function notifyGroup(GroupNotification $notification)
    {
        $this->em->persist($notification);
        $this->em->flush();

        // TODO: push to the client ??
    }
}
