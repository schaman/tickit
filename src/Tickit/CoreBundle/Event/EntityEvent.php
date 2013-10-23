<?php

namespace Tickit\CoreBundle\Event;

/**
 * Entity related event.
 *
 * @package Tickit\CoreBundle\Event
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class EntityEvent extends AbstractVetoableEvent
{
    /**
     * The entity that is to be deleted
     *
     * @var mixed
     */
    private $entity;

    /**
     * Constructor.
     *
     * @param mixed $entity The entity related to this event
     */
    public function __construct($entity)
    {
        $this->entity = $entity;
    }

    /**
     * Gets the entity on this event
     *
     * @return mixed
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * Sets the entity on this event
     *
     * @param mixed $entity The entity to set
     *
     * @return mixed
     */
    public function setEntity($entity)
    {
        $this->entity = $entity;
    }
}
