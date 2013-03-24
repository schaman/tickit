<?php

namespace Tickit\ProjectBundle\Tests\Controller;

use Tickit\CoreBundle\Tests\AbstractFunctionalTest;
use Tickit\ProjectBundle\Manager\ProjectManager;
use Tickit\UserBundle\Entity\User;

/**
 * Tests for the ProjectController
 *
 * @package Tickit\ProjectBundle\Tests\Controller
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class ProjectControllerTest extends AbstractFunctionalTest
{
    /**
     * User entity used for testing
     *
     * @var User
     */
    protected static $user;

    /**
     * Sets up user object for viewing project actions
     *
     * @return void
     */
    public static function setUpBeforeClass()
    {
        $user = new User();
        $user->addRole(User::ROLE_SUPER_ADMIN)
             ->setUsername('developer')
             ->setPassword('password');

        static::$user = $user;
    }

    /**
     * Ensures that the index action displays projects correctly
     *
     * @return void
     */
    public function testProjectActionsAreBehindFirewall()
    {
        $client = static::createClient();
        $router = $client->getContainer()->get('router');
        $client->followRedirects();

        $crawler = $client->request('get', $router->generate('project_index'));

        $this->assertEquals('Login', $crawler->filter('h2')->text(), '<h2> contains correct content');
    }

    /**
     * Tests the indexAction()
     *
     * Ensures that the indexAction() layout is as expected
     *
     * @return void
     */
    public function testIndexActionLayout()
    {
        $client = $this->getAuthenticatedClient(static::$user);

        $crawler = $client->request('get', '/projects');
        $this->assertEquals('Manage Projects', $crawler->filter('h2')->text(), '<h2> contains correct text');
    }

    /**
     * Tests the indexAction()
     *
     * Ensures that the indexAction() displays the correct number of projects
     *
     * @return void
     */
    public function testIndexActionDisplaysCorrectNumberOfProjects()
    {
        $client = $this->getAuthenticatedClient(static::$user);
        $container = $client->getContainer();

        /** @var ProjectManager $projectManager */
        $projectManager = $container->get('tickit_project.manager');
        $repository = $projectManager->getRepository();
        $totalProjects = count($repository->findAll());

        $crawler = $client->request('get', '/projects');
        $this->assertEquals($totalProjects, $crawler->filter('.data-list table tbody tr')->count(), '2 projects displayed correctly');
    }
}
