<?php

namespace Tickit\CoreBundle\Event;

use Symfony\Component\EventDispatcher\Event;

/**
 * Abstract implementation of an event that can be vetoed
 *
 * Events that extend this base class will gain the ability to be vetoed. This allows
 * logic to be implemented around subscribers flagging the event as being vetoed.
 *
 * @package Tickit\CoreBundle\Event
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class AbstractVetoableEvent extends Event
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
