<?php

namespace Tickit\ProjectBundle\Tests\Event\Dispatcher;

use Symfony\Component\EventDispatcher\Event;
use Tickit\CoreBundle\Tests\AbstractUnitTest;
use Tickit\ProjectBundle\Entity\Project;
use Tickit\ProjectBundle\Event\BeforeCreateEvent;
use Tickit\ProjectBundle\Event\BeforeDeleteEvent;
use Tickit\ProjectBundle\Event\BeforeUpdateEvent;
use Tickit\ProjectBundle\Event\CreateEvent;
use Tickit\ProjectBundle\Event\DeleteEvent;
use Tickit\ProjectBundle\Event\Dispatcher\ProjectEventDispatcher;
use Tickit\ProjectBundle\Event\UpdateEvent;
use Tickit\ProjectBundle\TickitProjectEvents;

/**
 * ProjectEventDispatcher tests
 *
 * @package Tickit\ProjectBundle\Tests\Event\Dispatcher
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
        $event = new BeforeCreateEvent($project);

        $eventDispatcher = $this->getLocalMockEventDispatcher(TickitProjectEvents::PROJECT_BEFORE_CREATE, $event);

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
        $event = new CreateEvent($project);

        $eventDispatcher = $this->getLocalMockEventDispatcher(TickitProjectEvents::PROJECT_CREATE, $event);

        $dispatcher = new ProjectEventDispatcher($eventDispatcher);
        $dispatcher->dispatchCreateEvent($project);
    }

    /**
     * Tests the dispatchBeforeUpdateEvent() method
     */
    public function testDispatchBeforeUpdateEventDispatchesEvent()
    {
        $project = new Project();
        $event = new BeforeUpdateEvent($project);

        $eventDispatcher = $this->getLocalMockEventDispatcher(TickitProjectEvents::PROJECT_BEFORE_UPDATE, $event);

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
        $event = new UpdateEvent($project, $originalProject);

        $eventDispatcher = $this->getLocalMockEventDispatcher(TickitProjectEvents::PROJECT_UPDATE, $event);

        $dispatcher = new ProjectEventDispatcher($eventDispatcher);
        $dispatcher->dispatchUpdateEvent($project, $originalProject);
    }

    /**
     * Tests the dispatchBeforeDeleteEvent() method
     */
    public function testDispatchBeforeDeleteEventDispatchesEvent()
    {
        $project = new Project();
        $event = new BeforeDeleteEvent($project);

        $eventDispatcher = $this->getLocalMockEventDispatcher(TickitProjectEvents::PROJECT_BEFORE_DELETE, $event);

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
        $event = new DeleteEvent($project);

        $eventDispatcher = $this->getLocalMockEventDispatcher(TickitProjectEvents::PROJECT_DELETE, $event);

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
