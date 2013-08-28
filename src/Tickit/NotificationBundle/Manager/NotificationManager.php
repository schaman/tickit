<?php

namespace Tickit\NotificationBundle\Manager;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Tickit\NotificationBundle\Entity\AbstractNotification;
use Tickit\NotificationBundle\Factory\NotificationFactory;

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
     * The notification factory
     *
     * @var NotificationFactory
     */
    protected $notificationFactory;

    /**
     * Constructor.
     *
     * @param Registry            $doctrine            The doctrine registry
     * @param NotificationFactory $notificationFactory The notification factory
     */
    public function __construct(Registry $doctrine, NotificationFactory $notificationFactory)
    {
        $this->doctrine = $doctrine;
        $this->notificationFactory = $notificationFactory;
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

    /**
     * Gets the notification factory
     *
     * @return NotificationFactory
     */
    public function getFactory()
    {
        return $this->notificationFactory;
    }
}
