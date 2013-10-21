<?php

namespace Tickit\ClientBundle\Tests\Event\Dispatcher;

use Tickit\ClientBundle\Entity\Client;
use Tickit\ClientBundle\Event\Dispatcher\ClientEventDispatcher;
use Tickit\ClientBundle\TickitClientEvents;
use Tickit\CoreBundle\Event\EntityEvent;
use Tickit\CoreBundle\Event\EntityModifiedEvent;
use Tickit\CoreBundle\Tests\AbstractUnitTest;

/**
 * ClientEventDispatcher tests
 *
 * @package Tickit\ClientBundle\Tests\Event\Dispatcher
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
