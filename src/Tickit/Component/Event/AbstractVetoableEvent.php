<?php

/*
 * Tickit, an open source web based bug management tool.
 *
 * Copyright (C) 2014  Tickit Project <http://tickit.io>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Tickit\Component\Event;

use Symfony\Component\EventDispatcher\Event;

/**
 * Abstract implementation of an event that can be vetoed
 *
 * Events that extend this base class will gain the ability to be vetoed. This allows
 * logic to be implemented around subscribers flagging the event as being vetoed.
 *
 * @package Tickit\Component\Event
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
abstract class AbstractVetoableEvent extends Event
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
