<?php

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
        $this->markTestIncomplete('Need to address the AbstractVetoableEvent::getEntity() issue');

        $entity = new \stdClass();
        $updatedEntity = new \stdClass();
        $updatedEntity->property = 1;
        $originalEntity = new \stdClass();

        $event = $this->getMockForAbstractClass('Tickit\CoreBundle\Event\AbstractVetoableEvent');
        $event->expects($this->once())
              ->method('getEntity')
              ->will($this->returnValue($updatedEntity));

        $manager = $this->getManager();
        $manager->expects($this->once())
                ->method('fetchEntityInOriginalState')
                ->with($originalEntity);

        $this->dispatcher->expects($this->once())
                         ->method('dispatchBeforeUpdateEvent')
                         ->with($entity)
                         ->will($this->returnValue($event));

        $this->em->expects($this->once())
                 ->method('flush');

        $this->dispatcher->expects($this->once())
                         ->method('dispatchUpdateEvent')
                         ->with($entity, $originalEntity);

        $returnedEntity = $manager->update($entity, true);
        $this->assertEquals($updatedEntity, $returnedEntity);
    }

    /**
     * Tests the update() method
     */
    public function testUpdateUpdatesEntityWithoutAutoFlush()
    {
        $this->markTestIncomplete('Need to address the AbstractVetoableEvent::getEntity() issue');

        $entity = new \stdClass();
        $updatedEntity = new \stdClass();
        $updatedEntity->property = 1;
        $originalEntity = new \stdClass();

        $event = $this->getMockForAbstractClass('Tickit\CoreBundle\Event\AbstractVetoableEvent');
        $event->expects($this->once())
              ->method('getEntity')
              ->will($this->returnValue($updatedEntity));

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

        $this->dispatcher->expects($this->once())
                         ->method('dispatchUpdateEvent')
                         ->with($entity, $originalEntity);

        $updatedEntity = $manager->update($entity, false);
        $this->assertEquals($entity, $updatedEntity);
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
