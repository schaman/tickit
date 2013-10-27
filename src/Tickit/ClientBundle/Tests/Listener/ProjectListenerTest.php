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
