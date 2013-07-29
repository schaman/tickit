<?php

namespace Tickit\NotificationBundle\Manager;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Tickit\NotificationBundle\Entity\AbstractNotification;

/**
 * Notification manager.
 *
 * Manages the creation and updating of notifications.
 *
 * @package Tickit\NotificationBundle\Manager
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class NotificationManager
{
    /**
     * The doctrine registry.
     *
     * @var Registry
     */
    protected $doctrine;

    /**
     * Constructor.
     *
     * @param Registry $doctrine The doctrine registry
     */
    public function __construct(Registry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    /**
     * Creates a new notification in the data layer.
     *
     * @param AbstractNotification $notification
     *
     * @return void
     */
    public function create(AbstractNotification $notification)
    {
        $em = $this->doctrine->getManager();
        $em->persist($notification);
        $em->flush();
    }
}
