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

namespace Tickit\Component\Event\Tests\Project\Dispatcher;

use Symfony\Component\EventDispatcher\Event;
use Tickit\Component\Test\AbstractUnitTest;
use Tickit\Component\Model\Project\Project;
use Tickit\Component\Event\Project\Dispatcher\ProjectEventDispatcher;
use Tickit\Component\Event\Project\ProjectEvents;
use Tickit\Component\Entity\Event\EntityEvent;
use Tickit\Component\Entity\Event\EntityModifiedEvent;

/**
 * ProjectEventDispatcher tests
 *
 * @package Tickit\Component\Event\Tests\Project\Dispatcher
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class ProjectEventDispatcherTest extends AbstractUnitTest
{
    /**
     * Tests the dispatchBeforeCreateEvent() method
     */
    public function testDispatchBeforeCreateEventDispatchesEvent()
    {
        $project = new Project();
        $event = new EntityEvent($project);

        $eventDispatcher = $this->getLocalMockEventDispatcher(ProjectEvents::PROJECT_BEFORE_CREATE, $event);

        $dispatcher = new ProjectEventDispatcher($eventDispatcher);
        $returnedEvent = $dispatcher->dispatchBeforeCreateEvent($project);

        $this->assertSame($event, $returnedEvent);
    }

    /**
     * Tests the dispatchCreateEvent() method
     */
    public function testDispatchCreateEventDispatchesEvent()
    {
        $project = new Project();
        $event = new EntityEvent($project);

        $eventDispatcher = $this->getLocalMockEventDispatcher(ProjectEvents::PROJECT_CREATE, $event);

        $dispatcher = new ProjectEventDispatcher($eventDispatcher);
        $dispatcher->dispatchCreateEvent($project);
    }

    /**
     * Tests the dispatchBeforeUpdateEvent() method
     */
    public function testDispatchBeforeUpdateEventDispatchesEvent()
    {
        $project = new Project();
        $event = new EntityEvent($project);

        $eventDispatcher = $this->getLocalMockEventDispatcher(ProjectEvents::PROJECT_BEFORE_UPDATE, $event);

        $dispatcher = new ProjectEventDispatcher($eventDispatcher);
        $returnedEvent = $dispatcher->dispatchBeforeUpdateEvent($project);

        $this->assertSame($event, $returnedEvent);
    }

    /**
     * Tests the dispatchCreateEvent() method
     */
    public function testDispatchUpdateEventDispatchesEvent()
    {
        $project = new Project();
        $originalProject = new Project();
        $event = new EntityModifiedEvent($project, $originalProject);

        $eventDispatcher = $this->getLocalMockEventDispatcher(ProjectEvents::PROJECT_UPDATE, $event);

        $dispatcher = new ProjectEventDispatcher($eventDispatcher);
        $dispatcher->dispatchUpdateEvent($project, $originalProject);
    }

    /**
     * Tests the dispatchBeforeDeleteEvent() method
     */
    public function testDispatchBeforeDeleteEventDispatchesEvent()
    {
        $project = new Project();
        $event = new EntityEvent($project);

        $eventDispatcher = $this->getLocalMockEventDispatcher(ProjectEvents::PROJECT_BEFORE_DELETE, $event);

        $dispatcher = new ProjectEventDispatcher($eventDispatcher);
        $returnedEvent = $dispatcher->dispatchBeforeDeleteEvent($project);

        $this->assertSame($event, $returnedEvent);
    }

    /**
     * Tests the dispatchDeleteEvent() method
     */
    public function testDispatchDeleteEventDispatchesEvent()
    {
        $project = new Project();
        $event = new EntityEvent($project);

        $eventDispatcher = $this->getLocalMockEventDispatcher(ProjectEvents::PROJECT_DELETE, $event);

        $dispatcher = new ProjectEventDispatcher($eventDispatcher);
        $dispatcher->dispatchDeleteEvent($project);
    }

    /**
     * Gets a ProjectEventDispatcher mock instance
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
