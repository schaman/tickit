<?php

namespace Tickit\UserBundle\Tests\Event\Dispatcher;

use Symfony\Component\EventDispatcher\Event;
use Tickit\CoreBundle\Tests\AbstractUnitTest;
use Tickit\UserBundle\Entity\User;
use Tickit\UserBundle\Event\BeforeCreateEvent;
use Tickit\UserBundle\Event\BeforeDeleteEvent;
use Tickit\UserBundle\Event\BeforeUpdateEvent;
use Tickit\UserBundle\Event\CreateEvent;
use Tickit\UserBundle\Event\DeleteEvent;
use Tickit\UserBundle\Event\Dispatcher\UserEventDispatcher;
use Tickit\UserBundle\Event\UpdateEvent;
use Tickit\UserBundle\TickitUserEvents;

/**
 * UserEventDispatcher tests
 *
 * @package Tickit\UserBundle\Tests\Event\Dispatcher
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
        $event = new BeforeCreateEvent($user);

        $eventDispatcher = $this->getLocalMockEventDispatcher(TickitUserEvents::USER_BEFORE_CREATE, $event);

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
        $event = new CreateEvent($user);

        $eventDispatcher = $this->getLocalMockEventDispatcher(TickitUserEvents::USER_CREATE, $event);

        $dispatcher = new UserEventDispatcher($eventDispatcher);
        $dispatcher->dispatchCreateEvent($user);
    }

    /**
     * Tests the dispatchBeforeUpdateEvent() method
     */
    public function testDispatchBeforeUpdateEventDispatchesEvent()
    {
        $user = new User();
        $event = new BeforeUpdateEvent($user);

        $eventDispatcher = $this->getLocalMockEventDispatcher(TickitUserEvents::USER_BEFORE_UPDATE, $event);

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
        $event = new UpdateEvent($user, $originalUser);

        $eventDispatcher = $this->getLocalMockEventDispatcher(TickitUserEvents::USER_UPDATE, $event);

        $dispatcher = new UserEventDispatcher($eventDispatcher);
        $dispatcher->dispatchUpdateEvent($user, $originalUser);
    }

    /**
     * Tests the dispatchBeforeDeleteEvent() method
     */
    public function testDispatchBeforeDeleteEventDispatchesEvent()
    {
        $user = new User();
        $event = new BeforeDeleteEvent($user);

        $eventDispatcher = $this->getLocalMockEventDispatcher(TickitUserEvents::USER_BEFORE_DELETE, $event);

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
        $event = new DeleteEvent($user);

        $eventDispatcher = $this->getLocalMockEventDispatcher(TickitUserEvents::USER_DELETE, $event);

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
