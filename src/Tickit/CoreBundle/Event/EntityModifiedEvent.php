<?php

namespace Tickit\CoreBundle\Event;

/**
 * Entity modification event.
 *
 * This event is used to represent a change to an entity's state in the
 * entity manager.
 *
 * @package Tickit\CoreBundle\Event
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class EntityModifiedEvent extends EntityEvent
{
    /**
     * The original entity state before it was modified
     *
     * @var mixed
     */
    private $originalEntity;

    /**
     * Constructor.
     *
     * @param mixed $entity         The entity related to this event
     * @param mixed $originalEntity The original entity state
     */
    public function __construct($entity, $originalEntity)
    {
        $this->setOriginalEntity($originalEntity);

        parent::__construct($entity);
    }


    /**
     * Gets the original entity - before modifications were applied
     *
     * @return mixed
     */
    public function getOriginalEntity()
    {
        return $this->originalEntity;
    }

    /**
     * Sets the original entity state on this object.
     *
     * This is a representation of the entity before the modifications were applied. It
     * is used to determine what has changed between the original state and the new
     * state.
     *
     * @param mixed $originalEntity
     */
    private function setOriginalEntity($originalEntity)
    {
        $this->originalEntity = $originalEntity;
    }
}
