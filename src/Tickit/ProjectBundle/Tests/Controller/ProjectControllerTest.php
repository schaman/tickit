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
     * Standard user entity used for testing
     *
     * @var User
     */
    protected static $developer;

    /**
     * Admin user entity used for testing
     *
     * @var User
     */
    protected static $admin;

    /**
     * Sets up user object for viewing project actions
     *
     * @return void
     */
    public static function setUpBeforeClass()
    {
        $developer = new User();
        $developer->addRole(User::ROLE_DEFAULT)
             ->setUsername('developer')
             ->setPassword('password');

        $admin = new User();
        $admin->addRole(User::ROLE_SUPER_ADMIN)
              ->setUsername('james')
              ->setPassword('password');

        static::$admin = $admin;
        static::$developer = $developer;
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
        $client = $this->getAuthenticatedClient(static::$developer);

        $crawler = $client->request('get', '/projects');
        $this->assertEquals(200, $client->getResponse()->getStatusCode(), 'indexAction() returns 200 response');
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
        $client = $this->getAuthenticatedClient(static::$developer);
        $container = $client->getContainer();

        /** @var ProjectManager $projectManager */
        $projectManager = $container->get('tickit_project.manager');
        $repository = $projectManager->getRepository();
        $totalProjects = count($repository->findAll());

        $crawler = $client->request('get', '/projects');
        $this->assertEquals($totalProjects, $crawler->filter('.data-list table tbody tr')->count());
    }

    /**
     * Tests the createAction()
     *
     * Ensures that the createAction() creates a project with valid details
     *
     * @return void
     */
    public function testCreateActionCreatesProject()
    {
        $client = $this->getAuthenticatedClient(static::$admin);

        $crawler = $client->request('get', '/projects');
        $totalProjects = $crawler->filter('div.data-list table tbody tr')->count();

        $crawler = $client->request('get', '/projects/add');
        $form = $crawler->selectButton('Save Project')->form(array(
            'tickit_project[name]' => 'Valid Project Name'
        ));
        $client->submit($form);
        $crawler = $client->followRedirect();
        $this->assertGreaterThan(0, $crawler->filter('div.flash-notice:contains("successfully")')->count());
        $this->assertEquals($totalProjects + 1, $crawler->filter('div.data-list table tbody tr')->count());
    }

    /**
     * Tests the createAction()
     *
     * Ensures that the createAction() displays validation messages for invalid details
     *
     * @return void
     */
    public function testCreateActionDisplaysErrorsForInvalidDetails()
    {
        $client = $this->getAuthenticatedClient(static::$admin);

        $crawler = $client->request('get', '/projects/add');
        $form = $crawler->selectButton('Save Project')->form();
        $crawler = $client->submit($form, array('tickit_project[name]' => ''));
        $this->assertGreaterThan(0, $crawler->filter('div#tickit_project ul li:contains("Please enter a project name")')->count());

        $longName = 'ajfwadpalfowagjiawjfwaidjwaofjwoagkowakfowakgowakfowagjwoajgwiadwadjwaijda' .
                    'adwiafjwaigjaiwofjawdawokfo'; //101 characters
        $crawler = $client->submit($form, array('tickit_project[name]' => $longName));
        $this->assertGreaterThan(0, $crawler->filter('div#tickit_project ul li:contains("Project name must be less than 100 characters")')->count());
    }
}
