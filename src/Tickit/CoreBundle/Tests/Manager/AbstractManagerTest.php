<?php

/*
 * 
 * Tickit, an source web based bug management tool.
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
 * 
 */

namespace Tickit\CoreBundle\Tests\Manager;

use Tickit\CoreBundle\Manager\AbstractManager;
use Tickit\CoreBundle\Tests\AbstractUnitTest;

/**
 * AbstractManager tests
 *
 * @package Tickit\CoreBundle\Tests\Manager
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class AbstractManagerTest extends AbstractUnitTest
{
    /**
     * Entity manager
     *
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $em;

    /**
     * Dispatcher
     *
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $dispatcher;

    /**
     * Setup
     */
    protected function setUp()
    {
        $this->em = $this->getMockEntityManager();
        $this->dispatcher = $this->getMockBuilder('Tickit\CoreBundle\Event\Dispatcher\AbstractEntityEventDispatcher')
                                 ->disableOriginalConstructor()
                                 ->getMock();
    }
    
    /**
     * Tests the create() method
     */
    public function testCreatePersistsEntityWithAutoFlush()
    {
        $entity = new \stdClass();
        $event = $this->getMockForAbstractClass('Tickit\CoreBundle\Event\AbstractVetoableEvent');

        $this->dispatcher->expects($this->once())
                         ->method('dispatchBeforeCreateEvent')
                         ->with($entity)
                         ->will($this->returnValue($event));

        $this->em->expects($this->once())
                 ->method('persist')
                 ->with($entity);

        $this->em->expects($this->once())
                 ->method('flush');

        $this->dispatcher->expects($this->once())
                         ->method('dispatchCreateEvent')
                         ->with($entity);

        $this->getManager()->create($entity, true);
    }

    /**
     * Tests the create() method
     */
    public function testCreatePersistsEntityWithoutAutoFlush()
    {
        $entity = new \stdClass();
        $event = $this->getMockForAbstractClass('Tickit\CoreBundle\Event\AbstractVetoableEvent');

        $this->dispatcher->expects($this->once())
                         ->method('dispatchBeforeCreateEvent')
                         ->with($entity)
                         ->will($this->returnValue($event));

        $this->em->expects($this->once())
                 ->method('persist')
                 ->with($entity);

        $this->em->expects($this->never())
                 ->method('flush');

        $this->dispatcher->expects($this->once())
                         ->method('dispatchCreateEvent')
                         ->with($entity);

        $this->getManager()->create($entity, false);
    }

    /**
     * Tests the create() method
     */
    public function testCreateDoesNotPersistEntityForVetoedEvent()
    {
        $entity = new \stdClass();
        $event = $this->getMockForAbstractClass('Tickit\CoreBundle\Event\AbstractVetoableEvent');
        $event->veto();

        $this->dispatcher->expects($this->once())
                         ->method('dispatchBeforeCreateEvent')
                         ->with($entity)
                         ->will($this->returnValue($event));

        $this->em->expects($this->never())
                 ->method('persist');

        $this->em->expects($this->never())
                 ->method('flush');

        $this->dispatcher->expects($this->never())
                         ->method('dispatchCreateEvent');

        $this->getManager()->create($entity, true);
    }

    /**
     * Tests the update() method
     */
    public function testUpdateUpdatesEntityWithAutoFlush()
    {
        $entity = new \stdClass();
        $entity->property = 1;

        $updatedEntity = new \stdClass();
        $updatedEntity->property = 2;

        $originalEntity = new \stdClass();
        $originalEntity->property = 3;

        $beforeUpdateEvent = $this->getMockBuilder('Tickit\CoreBundle\Event\EntityEvent')
                                  ->disableOriginalConstructor()
                                  ->getMock();

        $beforeUpdateEvent->expects($this->once())
                          ->method('isVetoed')
                          ->will($this->returnValue(false));

        $beforeUpdateEvent->expects($this->once())
                          ->method('getEntity')
                          ->will($this->returnValue($updatedEntity));

        $manager = $this->getManager();
        $manager->expects($this->once())
                ->method('fetchEntityInOriginalState')
                ->with($entity)
                ->will($this->returnValue($originalEntity));

        $this->dispatcher->expects($this->once())
                         ->method('dispatchBeforeUpdateEvent')
                         ->with($entity)
                         ->will($this->returnValue($beforeUpdateEvent));

        $this->em->expects($this->once())
                 ->method('flush');

        $this->dispatcher->expects($this->once())
                         ->method('dispatchUpdateEvent')
                         ->with($updatedEntity, $originalEntity);

        $returnedEntity = $manager->update($entity, true);
        $this->assertEquals($updatedEntity, $returnedEntity);
    }

    /**
     * Tests the update() method
     */
    public function testUpdateUpdatesEntityWithoutAutoFlush()
    {
        $entity = new \stdClass();
        $entity->property = 1;

        $updatedEntity = new \stdClass();
        $updatedEntity->property = 2;

        $originalEntity = new \stdClass();
        $originalEntity->property = 3;

        $beforeUpdateEvent = $this->getMockBuilder('Tickit\CoreBundle\Event\EntityEvent')
                                  ->disableOriginalConstructor()
                                  ->getMock();

        $beforeUpdateEvent->expects($this->once())
                          ->method('isVetoed')
                          ->will($this->returnValue(false));

        $beforeUpdateEvent->expects($this->once())
                          ->method('getEntity')
                          ->will($this->returnValue($updatedEntity));

        $manager = $this->getManager();
        $manager->expects($this->once())
                ->method('fetchEntityInOriginalState')
                ->with($entity)
                ->will($this->returnValue($originalEntity));

        $this->dispatcher->expects($this->once())
                         ->method('dispatchBeforeUpdateEvent')
                         ->with($entity)
                         ->will($this->returnValue($beforeUpdateEvent));

        $this->em->expects($this->never())
                 ->method('flush');

        $this->dispatcher->expects($this->once())
                         ->method('dispatchUpdateEvent')
                         ->with($updatedEntity, $originalEntity);

        $returnedEntity = $manager->update($entity, false);
        $this->assertEquals($updatedEntity, $returnedEntity);
    }

    /**
     * Tests the update() method
     */
    public function testUpdateDoesNotUpdateUserForVetoedEvent()
    {
        $entity = new \stdClass();
        $originalEntity = new \stdClass();

        $event = $this->getMockForAbstractClass('Tickit\CoreBundle\Event\AbstractVetoableEvent');
        $event->veto();

        $manager = $this->getManager();
        $manager->expects($this->once())
                ->method('fetchEntityInOriginalState')
                ->with($originalEntity);

        $this->dispatcher->expects($this->once())
                         ->method('dispatchBeforeUpdateEvent')
                         ->with($entity)
                         ->will($this->returnValue($event));

        $this->em->expects($this->never())
                 ->method('flush');

        $this->dispatcher->expects($this->never())
                         ->method('dispatchUpdateEvent');

        $updatedEntity = $manager->update($entity, false);
        $this->assertNull($updatedEntity);
    }

    /**
     * Tests the delete() method
     */
    public function testDeleteRemovesEntity()
    {
        $entity = new \stdClass();

        $event = $this->getMockForAbstractClass('Tickit\CoreBundle\Event\AbstractVetoableEvent');

        $this->dispatcher->expects($this->once())
                         ->method('dispatchBeforeDeleteEvent')
                         ->with($entity)
                         ->will($this->returnValue($event));

        $this->em->expects($this->once())
                 ->method('remove')
                 ->with($entity);

        $this->em->expects($this->once())
                 ->method('flush');

        $this->dispatcher->expects($this->once())
                         ->method('dispatchDeleteEvent')
                         ->with($entity);

        $this->getManager()->delete($entity);
    }

    /**
     * Tests the delete() method
     */
    public function testDeleteDoesNotRemoveEntityForVetoedEvent()
    {
        $entity = new \stdClass();

        $event = $this->getMockForAbstractClass('Tickit\CoreBundle\Event\AbstractVetoableEvent');
        $event->veto();

        $this->dispatcher->expects($this->once())
                         ->method('dispatchBeforeDeleteEvent')
                         ->with($entity)
                         ->will($this->returnValue($event));

        $this->em->expects($this->never())
                 ->method('remove');

        $this->em->expects($this->never())
                 ->method('flush');

        $this->dispatcher->expects($this->never())
                         ->method('dispatchDeleteEvent');

        $this->getManager()->delete($entity);
    }

    /**
     * Gets mock AbstractManager
     *
     * @return AbstractManager
     */
    private function getManager()
    {
        return $this->getMockForAbstractClass(
            'Tickit\CoreBundle\Manager\AbstractManager',
            array($this->em, $this->dispatcher)
        );
    }
}
