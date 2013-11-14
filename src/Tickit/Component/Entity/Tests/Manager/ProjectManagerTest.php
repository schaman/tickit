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

namespace Tickit\Component\Entity\Tests\Manager;

use Doctrine\Common\Collections\ArrayCollection;
use Tickit\Component\Entity\Event\EntityEvent;
use Tickit\Component\Test\AbstractUnitTest;
use Tickit\Component\Model\Project\ChoiceAttributeValue;
use Tickit\Component\Model\Project\LiteralAttributeValue;
use Tickit\Component\Model\Project\Project;
use Tickit\Component\Entity\Manager\ProjectManager;

/**
 * Tests for the project manager
 *
 * @package Tickit\Bundle\ProjectBundle\Tests\Manager
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class ProjectManagerTest extends AbstractUnitTest
{
    /**
     * Project repository
     *
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $projectRepository;

    /**
     * Entity manager
     *
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $em;

    /**
     * Event dispatcher
     *
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $dispatcher;

    /**
     * Setup
     */
    protected function setUp()
    {
        $this->projectRepository = $this->getMockBuilder('Tickit\Bundle\ProjectBundle\Doctrine\Repository\ProjectRepository')
                                        ->disableOriginalConstructor()
                                        ->getMock();

        $this->em = $this->getMockEntityManager();

        $this->dispatcher = $this->getMockBuilder('Tickit\Bundle\ProjectBundle\Event\Dispatcher\ProjectEventDispatcher')
                                 ->disableOriginalConstructor()
                                 ->getMock();
    }

    /**
     * Tests the getRepository() method
     */
    public function testGetRepositoryReturnsCorrectInstance()
    {
        $this->assertSame($this->projectRepository, $this->getManager()->getRepository());
    }

    /**
     * Tests the create() method
     */
    public function testCreatePersistsEntity()
    {
        $project = new Project();
        $project->setAttributes(new ArrayCollection(array(new LiteralAttributeValue(), new ChoiceAttributeValue())));

        $this->trainDispatcherBeforeCreateToReturnEvent($project, new EntityEvent($project));

        $this->em->expects($this->once())
                 ->method('persist')
                 ->with($project);

        $this->em->expects($this->exactly(2))
                 ->method('flush');

        $this->dispatcher->expects($this->once())
                         ->method('dispatchCreateEvent')
                         ->with($project);

        $persistedProject = $this->getManager()->create($project);
        $this->assertEquals($project, $persistedProject);
        $this->assertEquals($project->getAttributes(), $persistedProject->getAttributes());
    }

    /**
     * Tests the create() method
     */
    public function testCreateDoesNotPersistEntityWhenEventIsVetoed()
    {
        $project = new Project();
        $project->setAttributes(new ArrayCollection(array(new LiteralAttributeValue(), new ChoiceAttributeValue())));

        $event = new EntityEvent($project);
        $event->veto();

        $this->trainDispatcherBeforeCreateToReturnEvent($project, $event);

        $this->em->expects($this->never())
                 ->method('persist');

        $this->em->expects($this->never())
                 ->method('flush');

        $this->dispatcher->expects($this->never())
                         ->method('dispatchCreateEvent');

        $persistedProject = $this->getManager()->create($project);
        $this->assertNull($persistedProject);
    }

    /**
     * Gets an instance of the project manager
     *
     * @return \Tickit\Component\Entity\Manager\ProjectManager
     */
    private function getManager()
    {
        return new \Tickit\Component\Entity\Manager\ProjectManager($this->projectRepository, $this->em, $this->dispatcher);
    }

    private function trainDispatcherBeforeCreateToReturnEvent(Project $project, EntityEvent $event)
    {
        $this->dispatcher->expects($this->once())
                         ->method('dispatchBeforeCreateEvent')
                         ->with($project)
                         ->will($this->returnValue($event));
    }
}
