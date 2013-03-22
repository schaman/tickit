<?php

namespace Tickit\ProjectBundle\Tests\Manager;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Tickit\ProjectBundle\Entity\Project;
use Tickit\ProjectBundle\Manager\ProjectManager;

/**
 * Tests for the project manager
 *
 * @author James Halsall <james.t.halsall@googlemail.com>
 */
class ProjectManagerTest extends WebTestCase
{
    /**
     * Test to ensure that the service exists in the container
     *
     * @return void
     */
    public function testServiceExists()
    {
        $container = static::createClient()->getContainer();
        $manager = $container->get('tickit_project.manager');

        $this->assertInstanceOf('\Tickit\ProjectBundle\Manager\ProjectManager', $manager);
    }

    /**
     * Tests the persist() method
     *
     * Ensures that the method persists a Project to the entity manager
     *
     * @return void
     */
    public function testCreateCreatesProject()
    {
        $container = static::createClient()->getContainer();
        /** @var ProjectManager $manager  */
        $manager = $container->get('tickit_project.manager');

        $name = uniqid('Project ');
        $project = new Project();
        $project->setName($name);
        $project = $manager->create($project);

        $persistedProject = $manager->getRepository()
                                    ->find($project->getId());

        $this->assertEquals($project->getName(), $persistedProject->getName(), 'Created project has same name');
    }
}