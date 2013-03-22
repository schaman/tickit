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
     * Project entity for tests
     *
     * @var Project
     */
    protected $project;

    /**
     * Sets up entities for each test
     *
     * @return void
     */
    public function setUp()
    {
        $project = new Project();
        $project->setName(uniqid('Project ' . microtime()));

        $this->project = $project;
    }

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
     * Tests the create() method
     *
     * Ensures that the method creates a Project in the entity manager
     *
     * @return void
     */
    public function testCreateProject()
    {
        $manager = $this->getManager();
        $project = $manager->create($this->project);

        $persistedProject = $manager->getRepository()
                                    ->find($project);

        $this->assertEquals($project->getName(), $persistedProject->getName(), 'Created project has same name');
    }

    /**
     * Tests the update() method
     *
     * Ensures that the method updates a Project in the entity manager
     *
     * @return void
     */
    public function testUpdateProject()
    {
        $manager = $this->getManager();
        $project = $manager->create($this->project);
        $repository = $manager->getRepository();

        $createdProject = $repository->find($project->getId());

        $createdProject->setName('New project name');
        $manager->update($createdProject);

        $updatedProject = $repository->find($createdProject->getId());

        $this->assertEquals($createdProject->getName(), $updatedProject->getName(), 'Updated project has same name');
    }

    /**
     * Tear down the test and unset entities
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->project);
    }

    /**
     * Gets an instance of the project manager
     *
     * @return ProjectManager
     */
    protected function getManager()
    {
        $container = static::createClient()->getContainer();
        $manager = $container->get('tickit_project.manager');

        return $manager;
    }
}
