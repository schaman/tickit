<?php

/*
 * Tickit, an open source web based bug management tool.
 *
 * Copyright (C) 2014  Tickit Project <http://tickit.io>
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

namespace Tickit\Component\Notification\Manager;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Tickit\Component\Notification\Event\NotificationEvents;
use Tickit\Component\Notification\Event\UserNotificationEvent;
use Tickit\Component\Notification\Factory\NotificationFactory;
use Tickit\Component\Notification\Model\UserNotification;

/**
 * Notification manager.
 *
 * Manages the creation and updating of notifications.
 *
 * @package Tickit\Component\Notification\Manager
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
     * An event dispatcher
     *
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * Constructor.
     *
     * @param EntityManagerInterface   $entityManager       An entity manager
     * @param NotificationFactory      $notificationFactory The notification factory
     * @param EventDispatcherInterface $eventDispatcher     An event dispatcher
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        NotificationFactory $notificationFactory,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->em = $entityManager;
        $this->notificationFactory = $notificationFactory;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * Creates a new user notification in the data layer.
     *
     * Also dispatches an event to notify listeners.
     *
     * @param UserNotification $notification The user notification to create
     *
     * @return void
     */
    public function createUserNotification(UserNotification $notification)
    {
        $this->em->persist($notification);
        $this->em->flush();

        $this->eventDispatcher->dispatch(NotificationEvents::NOTIFY_USER, new UserNotificationEvent($notification));
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
