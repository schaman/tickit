<?php

namespace Tickit\ClientBundle\Tests\Listener;

use Tickit\ClientBundle\Entity\Client;
use Tickit\ClientBundle\Listener\ProjectListener;
use Tickit\CoreBundle\Event\EntityEvent;
use Tickit\CoreBundle\Tests\AbstractUnitTest;
use Tickit\ProjectBundle\Entity\Project;

/**
 * ProjectListener tests
 *
 * @package Tickit\ClientBundle\Tests\Listener
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class ProjectListenerTest extends AbstractUnitTest
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $em;

    /**
     * Setup
     */
    protected function setUp()
    {
        $this->em = $this->getMockEntityManager();
    }
    
    /**
     * Tests the onProjectCreate() method
     */
    public function testOnProjectCreateIncrementsClientProjectCount()
    {
        $client = new Client();
        $client->incrementTotalProjects();

        $project = new Project();
        $project->setClient($client);
        $event = new EntityEvent($project);

        $this->em->expects($this->once())
                 ->method('flush');

        $this->getListener()->onProjectCreate($event);

        $this->assertEquals(2, $event->getEntity()->getClient()->getTotalProjects());
    }

    /**
     * Tests the onProjectDelete() method
     */
    public function testOnProjectDeleteDecrementsClientProjectCount()
    {
        $client = new Client();
        $client->incrementTotalProjects();

        $project = new Project();
        $project->setClient($client);
        $event = new EntityEvent($project);

        $this->em->expects($this->once())
                 ->method('flush');

        $this->getListener()->onProjectDelete($event);

        $this->assertEquals(0, $event->getEntity()->getClient()->getTotalProjects());
    }

    /**
     * Gets a new listener instance
     *
     * @return ProjectListener
     */
    private function getListener()
    {
        return new ProjectListener($this->em);
    }
}
 