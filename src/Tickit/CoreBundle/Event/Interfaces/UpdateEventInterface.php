<?php

namespace Tickit\CoreBundle\Event\Interfaces;

/**
 * Interface for entity update events.
 *
 * Guarantees a way of retrieving the original entity state from the
 * event object.
 *
 * @package Tickit\CoreBundle\Event\Interfaces
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
interface UpdateEventInterface
{
    /**
     * Returns the entity in its original state.
     *
     * The "original state" of the entity should be as it was before the updates
     * were applied. Usually this is a copy of the entity retrieved from the data
     * layer and stored on the event object via its constructor
     *
     * @return object
     */
    function getOriginalEntity();
}
