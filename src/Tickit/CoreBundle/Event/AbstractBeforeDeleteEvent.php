<?php

namespace Tickit\CoreBundle\Event;

use Symfony\Component\EventDispatcher\Event;

/**
 * Abstract implementation of a "before delete" event
 *
 * This event is dispatched when any "before delete" events take place
 * in the application. It provides an interface for listeners to prevent the
 * delete from taking place (if the dispatcher supports it).
 *
 * @package Tickit\CoreBundle\Event
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class AbstractBeforeDeleteEvent extends Event
{
    /**
     * Boolean value indicating if this event has been vetoed
     *
     * @var boolean
     */
    protected $vetoed = false;

    /**
     * Flags this delete event as being vetoed
     *
     * @return void
     */
    public function veto()
    {
        $this->vetoed = true;
    }

    /**
     * Returns true if the delete event has been vetoed
     *
     * @return boolean
     */
    public function isVetoed()
    {
        return $this->vetoed;
    }
}
