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

namespace Tickit\Bundle\ClientBundle\Listener;

use Doctrine\ORM\EntityManagerInterface;
use Tickit\Component\Model\Client\Client;
use Tickit\Component\Entity\Event\EntityEvent;

/**
 * Project listener.
 *
 * Responsible for integrating the clients with the projects that they own.
 *
 * @package Tickit\Bundle\ClientBundle\Listener
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class ProjectListener
{
    /**
     * An entity manager
     *
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * Constructor.
     *
     * @param EntityManagerInterface $em An entity manager
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->entityManager = $em;
    }

    /**
     * Fired when the "tickit_project.event.delete" event is triggered.
     *
     * Responsible for incrementing a client's total project figure
     * when a new project is created.
     *
     * @param EntityEvent $event The event object
     */
    public function onProjectCreate(EntityEvent $event)
    {
        /** @var Client $client */
        $client = $event->getEntity()->getClient();

        $client->incrementTotalProjects();
        $this->entityManager->flush();
    }

    /**
     * Fired when the "tickit_project.event.create" event is triggered.
     *
     * Responsible for decrementing a client's total project figure
     * when one of their projects is deleted.
     *
     * @param EntityEvent $event The event object
     */
    public function onProjectDelete(EntityEvent $event)
    {
        /** @var Client $client */
        $client = $event->getEntity()->getClient();

        $client->decrementTotalProjects();
        $this->entityManager->flush();
    }
}
