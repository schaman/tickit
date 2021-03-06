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

namespace Tickit\Component\Event\Tests;

use Tickit\Component\Event\AbstractVetoableEvent;

/**
 * Tests for the AbstractVetoableEvent object
 *
 * @package Tickit\Component\Event\Tests
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class AbstractVetoableEventTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Ensures that the AbstractVetoableEvent object is a descendant of the event class
     *
     * @return void
     */
    public function testAbstractIsAnEvent()
    {
        $event = $this->getMockForAbstractClass('\Tickit\Component\Event\AbstractVetoableEvent');
        $this->assertInstanceOf('Symfony\Component\EventDispatcher\Event', $event);
    }

    /**
     * Tests the isVetoed() method
     *
     * Ensures that the method returns true and false in the correct scenarios
     *
     * @return void
     */
    public function testIsVetoedReturnsCorrectly()
    {
        /** @var AbstractVetoableEvent $event  */
        $event = $this->getMockForAbstractClass('\Tickit\Component\Event\AbstractVetoableEvent');

        $this->assertFalse($event->isVetoed(), 'isVeoted() correctly returns false initially');
        $event->veto();
        $this->assertTrue($event->isVetoed(), 'isVetoed() correctly returns true after calling veto()');
    }
}
