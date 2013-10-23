<?php

namespace Tickit\NotificationBundle\Manager;

use Doctrine\ORM\EntityManagerInterface;
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
     * An entity manager
     *
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * The notification factory
     *
     * @var NotificationFactory
     */
    protected $notificationFactory;

    /**
     * Constructor.
     *
     * @param EntityManagerInterface $entityManager       An entity manager
     * @param NotificationFactory    $notificationFactory The notification factory
     */
    public function __construct(EntityManagerInterface $entityManager, NotificationFactory $notificationFactory)
    {
        $this->em = $entityManager;
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
        $this->em->persist($notification);
        $this->em->flush();
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
