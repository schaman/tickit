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

namespace Tickit\Bundle\ClientBundle\Tests\Event\Dispatcher;

use Tickit\Component\Model\Client\Client;
use Tickit\Bundle\ClientBundle\Event\Dispatcher\ClientEventDispatcher;
use Tickit\Bundle\ClientBundle\TickitClientEvents;
use Tickit\Bundle\CoreBundle\Tests\AbstractUnitTest;
use Tickit\Component\Entity\Event\EntityEvent;
use Tickit\Component\Entity\Event\EntityModifiedEvent;

/**
 * ClientEventDispatcher tests
 *
 * @package Tickit\Bundle\ClientBundle\Tests\Event\Dispatcher
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class ClientEventDispatcherTest extends AbstractUnitTest
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $eventDispatcher;

    /**
     * Setup
     */
    protected function setUp()
    {
        $this->eventDispatcher = $this->getMockEventDispatcher();
    }
    
    /**
     * Tests the dispatchBeforeCreateEvent() method
     *
     */
    public function testDispatchBeforeCreateEventDispatchesCorrectEvent()
    {
        $entity = new Client();
        $event = new EntityEvent($entity);

        $this->trainDispatcherToDispatchEvent(TickitClientEvents::CLIENT_BEFORE_CREATE, $event);

        $this->assertEquals($event, $this->getDispatcher()->dispatchBeforeCreateEvent($entity));
    }

    /**
     * Tests the dispatchCreateEvent() method
     */
    public function testDispatchCreateEventDispatchesCorrectEvent()
    {
        $entity = new Client();
        $event = new EntityEvent($entity);

        $this->trainDispatcherToDispatchEvent(TickitClientEvents::CLIENT_CREATE, $event);

        $this->getDispatcher()->dispatchCreateEvent($entity);
    }

    /**
     * Tests the dispatchBeforeUpdateEvent() method
     */
    public function testDispatchBeforeUpdateEventDispatchesCorrectEvent()
    {
        $entity = new Client();
        $event = new EntityEvent($entity);

        $this->trainDispatcherToDispatchEvent(TickitClientEvents::CLIENT_BEFORE_UPDATE, $event);

        $this->assertEquals($event, $this->getDispatcher()->dispatchBeforeUpdateEvent($entity));
    }

    /**
     * Tests the dispatchUpdateEvent() method
     */
    public function testDispatchUpdateEventDispatchesCorrectEvent()
    {
        $entity = new Client();
        $originalEntity = new Client();
        $event = new EntityModifiedEvent($entity, $originalEntity);

        $this->trainDispatcherToDispatchEvent(TickitClientEvents::CLIENT_UPDATE, $event);

        $this->getDispatcher()->dispatchUpdateEvent($entity, $originalEntity);
    }

    /**
     * Tests the dispatchBeforeDeleteEvent() method
     */
    public function testDispatchBeforeDeleteEventDispatchesCorrectEvent()
    {
        $entity = new Client();
        $event = new EntityEvent($entity);

        $this->trainDispatcherToDispatchEvent(TickitClientEvents::CLIENT_BEFORE_DELETE, $event);

        $this->assertEquals($event, $this->getDispatcher()->dispatchBeforeDeleteEvent($entity));
    }

    /**
     * Tests the dispatchDeleteEvent() method
     */
    public function testDispatchDeleteEventDispatchesCorrectEvent()
    {
        $entity = new Client();
        $event = new EntityEvent($entity);

        $this->trainDispatcherToDispatchEvent(TickitClientEvents::CLIENT_DELETE, $event);

        $this->getDispatcher()->dispatchDeleteEvent($entity);
    }

    /**
     * Gets a new event dispatcher
     *
     * @return ClientEventDispatcher
     */
    private function getDispatcher()
    {
        return new ClientEventDispatcher($this->eventDispatcher);
    }

    private function trainDispatcherToDispatchEvent($name, $event)
    {
        $this->eventDispatcher->expects($this->once())
                              ->method('dispatch')
                              ->with($name, $event)
                              ->will($this->returnArgument(1));
    }
}
