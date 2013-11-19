<?php

/*
 * Tickit, an open source web based bug management tool.
 * 
 * Copyright (C) 2013  Tickit Project <http://tickit.io>
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

namespace Tickit\Bundle\UserBundle\Tests\Event\Dispatcher;

use Symfony\Component\EventDispatcher\Event;
use Tickit\Component\Test\AbstractUnitTest;
use Tickit\Component\Model\User\User;
use Tickit\Bundle\UserBundle\Event\Dispatcher\UserEventDispatcher;
use Tickit\Component\Event\User\UserEvents;
use Tickit\Component\Entity\Event\EntityEvent;
use Tickit\Component\Entity\Event\EntityModifiedEvent;

/**
 * UserEventDispatcher tests
 *
 * @package Tickit\Bundle\UserBundle\Tests\Event\Dispatcher
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class UserEventDispatcherTest extends AbstractUnitTest
{
    /**
     * Tests the dispatchBeforeCreateEvent() method
     */
    public function testDispatchBeforeCreateEventDispatchesEvent()
    {
        $user = new User();
        $event = new EntityEvent($user);

        $eventDispatcher = $this->getLocalMockEventDispatcher(UserEvents::USER_BEFORE_CREATE, $event);

        $dispatcher = new UserEventDispatcher($eventDispatcher);
        $returnedEvent = $dispatcher->dispatchBeforeCreateEvent($user);

        $this->assertSame($event, $returnedEvent);
    }

    /**
     * Tests the dispatchCreateEvent() method
     */
    public function testDispatchCreateEventDispatchesEvent()
    {
        $user = new User();
        $event = new EntityEvent($user);

        $eventDispatcher = $this->getLocalMockEventDispatcher(UserEvents::USER_CREATE, $event);

        $dispatcher = new UserEventDispatcher($eventDispatcher);
        $dispatcher->dispatchCreateEvent($user);
    }

    /**
     * Tests the dispatchBeforeUpdateEvent() method
     */
    public function testDispatchBeforeUpdateEventDispatchesEvent()
    {
        $user = new User();
        $event = new EntityEvent($user);

        $eventDispatcher = $this->getLocalMockEventDispatcher(UserEvents::USER_BEFORE_UPDATE, $event);

        $dispatcher = new UserEventDispatcher($eventDispatcher);
        $returnedEvent = $dispatcher->dispatchBeforeUpdateEvent($user);

        $this->assertSame($event, $returnedEvent);
    }

    /**
     * Tests the dispatchCreateEvent() method
     */
    public function testDispatchUpdateEventDispatchesEvent()
    {
        $user = new User();
        $originalUser = new User();
        $event = new EntityModifiedEvent($user, $originalUser);

        $eventDispatcher = $this->getLocalMockEventDispatcher(UserEvents::USER_UPDATE, $event);

        $dispatcher = new UserEventDispatcher($eventDispatcher);
        $dispatcher->dispatchUpdateEvent($user, $originalUser);
    }

    /**
     * Tests the dispatchBeforeDeleteEvent() method
     */
    public function testDispatchBeforeDeleteEventDispatchesEvent()
    {
        $user = new User();
        $event = new EntityEvent($user);

        $eventDispatcher = $this->getLocalMockEventDispatcher(UserEvents::USER_BEFORE_DELETE, $event);

        $dispatcher = new UserEventDispatcher($eventDispatcher);
        $returnedEvent = $dispatcher->dispatchBeforeDeleteEvent($user);

        $this->assertSame($event, $returnedEvent);
    }

    /**
     * Tests the dispatchDeleteEvent() method
     */
    public function testDispatchDeleteEventDispatchesEvent()
    {
        $user = new User();
        $event = new EntityEvent($user);

        $eventDispatcher = $this->getLocalMockEventDispatcher(UserEvents::USER_DELETE, $event);

        $dispatcher = new UserEventDispatcher($eventDispatcher);
        $dispatcher->dispatchDeleteEvent($user);
    }

    /**
     * Gets a UserEventDispatcher mock instance
     *
     * @param string $eventName The name of the event that gets dispatched
     * @param Event  $event     The event object
     *
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getLocalMockEventDispatcher($eventName, Event $event)
    {
        $eventDispatcher = parent::getMockEventDispatcher();
        $eventDispatcher->expects($this->once())
                        ->method('dispatch')
                        ->with($eventName, $event)
                        ->will($this->returnValue($event));

        return $eventDispatcher;
    }
}
