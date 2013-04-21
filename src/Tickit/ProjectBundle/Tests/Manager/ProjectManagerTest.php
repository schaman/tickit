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
     * Tests the getRepository() method
     *
     * Ensures that a valid repository instance is returned by the method
     *
     * @return void
     */
    public function testGetRepositoryReturnsValidInstance()
    {
        $manager = $this->getManager();
        $repository = $manager->getRepository();

        $this->assertInstanceOf('\Tickit\ProjectBundle\Entity\Repository\ProjectRepository', $repository);
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
                                    ->find($project->getId());

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

        $this->assertNotEmpty($project->getId(), 'Project created successfully');

        $project->setName('New project name');
        $manager->update($project);

        $updatedProject = $repository->find($project->getId());

        $this->assertEquals($project->getName(), $updatedProject->getName(), 'Updated project has same name');
    }

    /**
     * Tests the delete() method
     *
     * Ensures that the method deletes a Project in the entity manager
     */
    public function testDeleteProject()
    {
        $manager = $this->getManager();
        $project = $manager->create($this->project);
        $repository = $manager->getRepository();

        $id = $project->getId();
        $this->assertNotEmpty($id, 'Project created successfully');

        $manager->delete($project);

        /** @var Project $findProject */
        $findProject = $repository->find($id);

        $this->assertEmpty($findProject, 'delete() method correctly removes project');
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
