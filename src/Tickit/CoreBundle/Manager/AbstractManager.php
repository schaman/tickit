<?php

namespace Tickit\CoreBundle\Manager;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\EventDispatcher\ContainerAwareEventDispatcher;
use Symfony\Component\EventDispatcher\Event;
use Tickit\CoreBundle\Entity\Interfaces\DeletableEntityInterface;
use Tickit\CoreBundle\Event\AbstractVetoableEvent;
use Tickit\ProjectBundle\Event\BeforeUpdateEvent;
use Tickit\ProjectBundle\Event\UpdateEvent;
use Tickit\ProjectBundle\TickitProjectEvents;

/**
 * Abstract entity manager
 *
 * Responsible for providing base functionality for all entity managers in
 * the application
 *
 * @package Tickit\CoreBundle\Manager
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
abstract class AbstractManager
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
     * Updates an entity by flushing changes to the entity manager.
     *
     * This method provides basic functionality for deletion, and fires methods in the
     * implementing class that will dispatch the relevant events
     *
     * @param object  $entity The entity to update in the entity manager
     * @param boolean $flush  True to automatically flush changes to the database, false otherwise (defaults to true)
     *
     * @return void
     */
    public function update($entity, $flush = true)
    {
        $class = $this->getEntityClassName();
        $originalProject = $this->em->find($class, $entity->getId());

        $beforeEvent = new BeforeUpdateEvent($entity);
        /** @var BeforeUpdateEvent $beforeEvent */
        $beforeEvent = $this->dispatcher->dispatch(TickitProjectEvents::PROJECT_BEFORE_UPDATE, $beforeEvent);

        // a subscriber may have updated the project so we re-fetch it from the event
        $entity = $beforeEvent->getProject();
        $this->em->persist($entity);

        if (false !== $flush) {
            $this->em->flush();
        }

        $event = new UpdateEvent($entity, $originalProject);
        $this->dispatcher->dispatch(TickitProjectEvents::PROJECT_UPDATE, $event);
    }

    /**
     * Deletes an entity from the entity manager.
     *
     * This method provides basic functionality for deletion, and fires methods in the
     * implementing class that will dispatch the relevant events
     *
     * @param DeletableEntityInterface $entity The entity to be deleted
     * @param boolean                  $flush  True to automatically flush the changes to the database (defaults to true)
     *
     * @return void
     */
    public function delete(DeletableEntityInterface $entity, $flush = true)
    {
        $beforeEvent = $this->dispatchBeforeDeleteEvent($entity);

        if ($beforeEvent->isVetoed()) {
            return;
        }

        $entity->delete();
        $this->em->persist($entity);

        if (false !== $flush) {
            $this->em->flush();
        }

        $this->dispatchDeleteEvent($entity);
    }

    /**
     * Returns the fully qualified entity class name that the implementing manager is responsible for
     *
     * @return string
     */
    abstract protected function getEntityClassName();

    /**
     * Dispatches events for the "before create" entity event
     *
     * The implementing class should return the event object for the "before create"
     * event to guarantee proper behaviour
     *
     * @param object $entity The entity that is about to be created
     *
     * @return AbstractVetoableEvent
     */
    abstract protected function dispatchBeforeCreateEvent($entity);

    /**
     * Dispatches events for the "create" entity event
     *
     * The implementing class should return the event object for the "create"
     * event to guarantee proper behaviour
     *
     * @param object $entity The entity that has just been created
     *
     * @return void
     */
    abstract protected function dispatchCreateEvent($entity);

    /**
     * Dispatches events for the "before update" entity event
     *
     * The implementing class should return the event object for the "before update"
     * event to guarantee proper behaviour
     *
     * @param object $entity The entity that is about to be updated
     *
     * @return AbstractVetoableEvent
     */
    abstract protected function dispatchBeforeUpdateEvent($entity);

    /**
     * Dispatches events for the "update" entity event
     *
     * The implementing class should return the event object for the "update"
     * event to guarantee proper behaviour
     *
     * @param object $entity         The entity that has just been updated
     * @param object $originalEntity The entity in its original state, before the updates were applied
     *
     * @return void
     */
    abstract protected function dispatchUpdateEvent($entity, $originalEntity);

    /**
     * Dispatches events for the "before delete" entity event
     *
     * The implementing class should return the event object for the "before delete"
     * event to guarantee proper behaviour
     *
     * @param DeletableEntityInterface $entity The entity that is about to be deleted
     *
     * @return AbstractVetoableEvent
     */
    abstract protected function dispatchBeforeDeleteEvent(DeletableEntityInterface $entity);

    /**
     * Dispatches events for the "delete" entity event
     *
     * The implementing class should return the event object for the "delete"
     * event to guarantee proper behaviour
     *
     * @param DeletableEntityInterface $entity The entity that has just been deleted in the entity manager
     *
     * @return void
     */
    abstract protected function dispatchDeleteEvent(DeletableEntityInterface $entity);
}
