<?php

namespace Tickit\CoreBundle\Event\Interfaces;

/**
 * Interface for entity aware events.
 *
 * Any events implementing this interface are guaranteeing that they are
 * responsible for an entity. This proves useful when an object is responsible
 * for the management of events when it does not have insightful details about
 * the entities on those events.
 *
 * @package Tickit\CoreBundle\Event\Interfaces
 * @author  James Halsall <james.t.halsall@googlemail.com>
 * @see     Tickit\CoreBundle\Manager\AbstractManager
 */
interface EntityAwareEventInterface
{
    /**
     * Gets the entity associated with this event
     *
     * @return object
     */
    public function getEntity();

    /**
     * Sets the entity associated with this event
     *
     * @param object $entity The entity to attach to the event
     */
    public function setEntity($entity);
}
