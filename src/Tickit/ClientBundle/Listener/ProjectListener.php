<?php

namespace Tickit\ClientBundle\Listener;

use Doctrine\ORM\EntityManagerInterface;
use Tickit\ClientBundle\Entity\Client;
use Tickit\CoreBundle\Event\EntityEvent;

/**
 * Project listener.
 *
 * Responsible for integrating the clients with the projects that they own.
 *
 * @package Tickit\ClientBundle\Listener
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
