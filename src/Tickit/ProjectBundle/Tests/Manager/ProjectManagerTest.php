<?php

namespace Tickit\ProjectBundle\Tests\Manager;

use Doctrine\Common\Collections\ArrayCollection;
use Tickit\CoreBundle\Tests\AbstractUnitTest;
use Tickit\ProjectBundle\Entity\ChoiceAttributeValue;
use Tickit\ProjectBundle\Entity\LiteralAttributeValue;
use Tickit\ProjectBundle\Entity\Project;
use Tickit\ProjectBundle\Event\BeforeCreateEvent;
use Tickit\ProjectBundle\Manager\ProjectManager;

/**
 * Tests for the project manager
 *
 * @package Tickit\ProjectBundle\Tests\Manager
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
        $this->projectRepository = $this->getMockBuilder('Tickit\ProjectBundle\Entity\Repository\ProjectRepository')
                                        ->disableOriginalConstructor()
                                        ->getMock();

        $this->em = $this->getMockEntityManager();

        $this->dispatcher = $this->getMockBuilder('Tickit\ProjectBundle\Event\Dispatcher\ProjectEventDispatcher')
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

        $this->dispatcher->expects($this->once())
                         ->method('dispatchBeforeCreateEvent')
                         ->with($project)
                         ->will($this->returnValue(new BeforeCreateEvent($project)));

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

        $event = new BeforeCreateEvent($project);
        $event->veto();

        $this->dispatcher->expects($this->once())
                         ->method('dispatchBeforeCreateEvent')
                         ->with($project)
                         ->will($this->returnValue($event));

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
     * @return ProjectManager
     */
    protected function getManager()
    {
        return new ProjectManager($this->projectRepository, $this->em, $this->dispatcher);
    }
}
