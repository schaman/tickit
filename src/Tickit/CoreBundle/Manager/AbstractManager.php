<?php

namespace Tickit\CoreBundle\Manager;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\Common\Persistence\ObjectManager;
use Tickit\CoreBundle\Entity\Interfaces\DeletableEntityInterface;
use Tickit\CoreBundle\Event\Dispatcher\AbstractEntityEventDispatcher;

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
     * @var AbstractEntityEventDispatcher
     */
    protected $dispatcher;

    /**
     * Constructor.
     *
     * @param Registry                      $doctrine   The doctrine registry service
     * @param AbstractEntityEventDispatcher $dispatcher The event dispatcher
     */
    public function __construct(Registry $doctrine, AbstractEntityEventDispatcher $dispatcher)
    {
        $this->em = $doctrine->getManager();
        $this->dispatcher = $dispatcher;
    }

    /**
     * Creates an entity by persisting and flushing changes to the entity manager.
     *
     * This method provides basic functionality for creation, and fires methods in the
     * dispatcher for the relevant events
     *
     * @param object  $entity The entity to update in the entity manager
     * @param boolean $flush  True to automatically flush changes to the database, false otherwise (defaults to true)
     *
     * @return void
     */
    public function create($entity, $flush = true)
    {
        $beforeEvent = $this->dispatcher->dispatchBeforeCreateEvent($entity);

        if ($beforeEvent->isVetoed()) {
            return;
        }

        $entity = $beforeEvent->getOriginalEntity();

        $this->em->persist($entity);
        if (false !== $flush) {
            $this->em->flush();
        }

        $this->dispatcher->dispatchCreateEvent($entity);
    }

    /**
     * Updates an entity by flushing changes to the entity manager.
     *
     * This method provides basic functionality for updating, and fires methods in the
     * dispatcher for the relevant events
     *
     * @param object  $entity The entity to update in the entity manager
     * @param boolean $flush  True to automatically flush changes to the database, false otherwise (defaults to true)
     *
     * @return void
     */
    public function update($entity, $flush = true)
    {
        $originalEntity = $this->fetchEntityInOriginalState($entity);

        $beforeEvent = $this->dispatcher->dispatchBeforeUpdateEvent($entity);

        // a subscriber may have updated the project so we re-fetch it from the event
        $entity = $beforeEvent->getOriginalEntity();
        $this->em->persist($entity);

        if (false !== $flush) {
            $this->em->flush();
        }

        $this->dispatcher->dispatchUpdateEvent($entity, $originalEntity);
    }

    /**
     * Deletes an entity from the entity manager.
     *
     * This method provides basic functionality for deletion, and fires methods in the
     * dispatcher for the relevant events
     *
     * @param DeletableEntityInterface $entity The entity to be deleted
     * @param boolean                  $flush  True to automatically flush the changes to the database (defaults to true)
     *
     * @return void
     */
    public function delete(DeletableEntityInterface $entity, $flush = true)
    {
        $beforeEvent = $this->dispatcher->dispatchBeforeDeleteEvent($entity);

        if ($beforeEvent->isVetoed()) {
            return;
        }

        $entity->delete();
        $this->em->persist($entity);

        if (false !== $flush) {
            $this->em->flush();
        }

        $this->dispatcher->dispatchDeleteEvent($entity);
    }

    /**
     * Returns the original entity from the entity manager.
     *
     * This method takes an entity and returns a copy in its original state
     * from the entity manager. This is used when dispatching entity update
     * events, so a before and after comparison can take place.
     *
     * @param object $entity The entity in its current state
     *
     * @return object
     */
    abstract protected function fetchEntityInOriginalState($entity);
}
