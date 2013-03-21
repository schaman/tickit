<?php

namespace Tickit\CoreBundle\Event\Dispatcher;

use Symfony\Component\EventDispatcher\ContainerAwareEventDispatcher;
use Tickit\CoreBundle\Entity\Interfaces\DeletableEntityInterface;
use Tickit\CoreBundle\Event\AbstractVetoableEvent;

/**
 * Abstract implementation of an entity event dispatcher
 *
 * @package Namespace\Class
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
abstract class AbstractEntityEventDispatcher
{
    /**
     * The dispatcher service
     *
     * @var ContainerAwareEventDispatcher
     */
    protected $dispatcher;

    /**
     * Constructor.
     *
     * @param ContainerAwareEventDispatcher $dispatcher The event dispatcher service
     */
    public function __construct(ContainerAwareEventDispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * Dispatches events for the "before create" event on the entity
     *
     * The implementing class should return the event object to guarantee
     * expected behaviour
     *
     * @param object $entity The project entity that is about to be created
     *
     * @return AbstractVetoableEvent
     */
    abstract public function dispatchBeforeCreateEvent($entity);

    /**
     * Dispatches events for the "create" event on the entity
     *
     * @param object $entity The project entity that has just been created
     *
     * @return void
     */
    abstract public function dispatchCreateEvent($entity);

    /**
     * Dispatches events for the "before update" event on the entity
     *
     * The implementing class should return the event object to guarantee
     * expected behaviour
     *
     * @param object $entity The entity that is about to be updated
     *
     * @return AbstractVetoableEvent
     */
    abstract public function dispatchBeforeUpdateEvent($entity);

    /**
     * Dispatches events for the "update" event on the entity
     *
     * @param object $entity         The entity that has just been updated
     * @param object $originalEntity The entity before any changes were applied
     *
     * @return void
     */
    abstract public function dispatchUpdateEvent($entity, $originalEntity);

    /**
     * Dispatches events for the "before delete" event on the entity
     *
     * The implementing class should return the event object to guarantee
     * expected behaviour
     *
     * @param DeletableEntityInterface $entity The entity that is about to be deleted
     *
     * @return AbstractVetoableEvent
     */
    abstract public function dispatchBeforeDeleteEvent(DeletableEntityInterface $entity);

    /**
     * Dispatches events for the "delete" event on the entity
     *
     * @param DeletableEntityInterface $entity The entity that has just been deleted in the entity manager
     *
     * @return void
     */
    abstract public function dispatchDeleteEvent(DeletableEntityInterface $entity);
}
