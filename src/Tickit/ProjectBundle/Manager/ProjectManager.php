<?php

namespace Tickit\ProjectBundle\Manager;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\EventDispatcher\ContainerAwareEventDispatcher;
use Tickit\ProjectBundle\Entity\Project;
use Tickit\ProjectBundle\Event\ProjectCreateEvent;
use Tickit\ProjectBundle\TickitProjectEvents;

/**
 * Project Manager
 *
 * Responsible for the management of project entities and their interaction
 * with the rest of the application
 *
 * @package Tickit\ProjectBundle\Manager
 * @author  James Halsall <jhalsall@rippleffect.com>
 */
class ProjectManager
{
    /**
     * Entity manager
     *
     * @var ObjectManager
     */
    protected $em;

    /**
     * The event dispatcher
     *
     * @var ContainerAwareEventDispatcher
     */
    protected $dispatcher;

    /**
     * Constructor.
     *
     * @param Registry                      $doctrine   The doctrine registry service
     * @param ContainerAwareEventDispatcher $dispatcher The event dispatcher
     */
    public function __construct(Registry $doctrine, ContainerAwareEventDispatcher $dispatcher)
    {
        $this->em = $doctrine->getManager();
        $this->dispatcher = $dispatcher;
    }

    /**
     * Persists a project entity to the database
     *
     * @param Project $project The project to persist
     * @param boolean $flush   True to automatically flush changes to the database, false otherwise (defaults to true)
     *
     * @return void
     */
    public function persist(Project $project, $flush = true)
    {
        $this->em->persist($project);

        if (false !== $flush) {
            $this->em->flush();
        }

        $event = new ProjectCreateEvent($project);
        $this->dispatcher->dispatch(TickitProjectEvents::PROJECT_CREATE, $event);
    }
}
