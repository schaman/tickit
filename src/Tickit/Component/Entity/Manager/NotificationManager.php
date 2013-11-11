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

namespace Tickit\Component\Entity\Manager;

use Doctrine\ORM\EntityManagerInterface;
use Tickit\Bundle\NotificationBundle\Entity\AbstractNotification;
use Tickit\Bundle\NotificationBundle\Factory\NotificationFactory;

/**
 * Notification manager.
 *
 * Manages the creation and updating of notifications.
 *
 * @package Tickit\Bundle\NotificationBundle\Manager
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
