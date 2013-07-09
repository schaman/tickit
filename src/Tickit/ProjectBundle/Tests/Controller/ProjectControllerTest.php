<?php

namespace Tickit\ProjectBundle\Tests\Controller;

use Tickit\CoreBundle\Tests\AbstractFunctionalTest;
use Tickit\ProjectBundle\Entity\Project;
use Tickit\ProjectBundle\Manager\ProjectManager;
use Tickit\UserBundle\Entity\User;

/**
 * Tests for the ProjectController
 *
 * @package Tickit\ProjectBundle\Tests\Controller
 * @author  James Halsall <james.t.halsall@googlemail.com>
 * @group   functional
 */
class ProjectControllerTest extends AbstractFunctionalTest
{
    /**
     * Sample project entity
     *
     * @var Project
     */
    protected static $project;

    /**
     * {@inheritDoc}
     *
     * @return void
     */
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        $doctrine = static::createClient()->getContainer()->get('doctrine');

        static::$project = $doctrine->getRepository('TickitProjectBundle:Project')
            ->findOneByName('Test Project 1');
    }

    /**
     * Makes sure project actions are not publicly accessible
     *
     * @return void
     */
    public function testProjectActionsAreBehindFirewall()
    {
        $client = static::createClient();
        $router = $client->getContainer()->get('router');

        $client->request('get', $router->generate('project_index'));
        $this->assertEquals(302, $client->getResponse()->getStatusCode());

        $client->followRedirect();
        $this->assertContains('/login', $client->getRequest()->getUri());
    }

    /**
     * Tests the addAction()
     *
     * Ensures that the addAction() creates a project with valid details
     *
     * @return void
     */
    public function testCreateActionCreatesProject()
    {
        $client = $this->getAuthenticatedClient(static::$admin);

        // fetch form (just to get the CSRF token)
        $crawler = $client->request('get', $this->generateRoute('project_create_form'));

        $projectName = __FUNCTION__ . time();

        $attribute0 = $crawler->filter('select[name="tickit_project[attributes][0][value]"] option:first-child')
                              ->attr('value');

        $attribute1 = array(
            'month' => $crawler->filter('select[name="tickit_project[attributes][1][value][month]"] option:first-child')
                               ->attr('value'),
            'day'   => $crawler->filter('select[name="tickit_project[attributes][1][value][day]"] option:first-child')
                               ->attr('value'),
            'year'  => $crawler->filter('select[name="tickit_project[attributes][1][value][year]"] option:first-child')
                               ->attr('value')
        );

        $attribute2 = $crawler->filter('input[name^="tickit_project[attributes][2][value]"]:first-child')
                              ->attr('value');

        $form = $crawler->selectButton('Save Project')->form(
            array(
                'tickit_project[name]' => $projectName,
                'tickit_project[attributes][0][value]' => $attribute0,
                'tickit_project[attributes][1][value][month]' => $attribute1['month'],
                'tickit_project[attributes][1][value][day]' => $attribute1['day'],
                'tickit_project[attributes][1][value][year]' => $attribute1['year'],
                'tickit_project[attributes][2][value]' => array($attribute2),
            )
        );
        $client->submit($form);

        $jsonResponse = json_decode($client->getResponse()->getContent());
        $this->assertTrue($jsonResponse->success);

        $doctrine = $this->createClient()->getContainer()->get('doctrine');
        $project  = $doctrine->getRepository('TickitProjectBundle:Project')->findOneByName($projectName);

        $this->assertInstanceOf('\Tickit\ProjectBundle\Entity\Project', $project);
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
        $this->markTestSkipped('Needs refactoring to new API format');

        $client = $this->getAuthenticatedClient(static::$admin);

        $crawler = $client->request('get', '/projects/create');
        $form = $crawler->selectButton('Save Project')->form();
        $crawler = $client->submit($form, array('tickit_project[name]' => ''));
        $this->assertGreaterThan(0, $crawler->filter('form ul li')->count());

        $longName = 'ajfwadpalfowagjiawjfwaidjwaofjwoagkowakfowakgowakfowagjwoajgwiadwadjwaijda' .
                    'adwiafjwaigjaiwofjawdawokfo'; //101 characters
        $crawler = $client->submit($form, array('tickit_project[name]' => $longName));
        $this->assertGreaterThan(0, $crawler->filter('form ul li')->count());
    }

    /**
     * Tests the editAction()
     *
     * Ensures that the editAction() updates a project with valid details
     *
     * @return void
     */
    public function testEditActionUpdatesProject()
    {
        $client = $this->getAuthenticatedClient(static::$admin);

        $oldProjectName = static::$project->getName();
        $newProjectName = $oldProjectName . ' ' . __FUNCTION__;

        // fetch form (just to get the CSRF token)
        $crawler = $client->request('get', $this->generateRoute('project_edit_form', array('id' => static::$project->getId())));

        $form = $crawler->selectButton('Save Changes')->form(
            array(
                'tickit_project[name]' => $newProjectName
            )
        );
        $client->submit($form);

        $jsonResponse = json_decode($client->getResponse()->getContent());
        $this->assertTrue($jsonResponse->success);

        $doctrine = $this->createClient()->getContainer()->get('doctrine');
        $project  = $doctrine->getRepository('TickitProjectBundle:Project')->find(static::$project->getId());

        $this->assertEquals($newProjectName, $project->getName());

        // revert back to old project name for other tests to still pass
        $project->setName($oldProjectName);
        $doctrine->getManager()->flush();
    }

    /**
     * Tests the editAction()
     *
     * Ensures that a 404 response is returned for an invalid project ID
     */
    public function testEditActionThrows404ForInvalidProjectId()
    {
        $client = $this->getAuthenticatedClient(static::$admin);

        $client->request('get', '/projects/edit/999999999999999999');
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }

    /**
     * Tests the deleteAction()
     *
     * Ensures that the deleteAction() removes a project
     *
     * @return void
     */
    public function testDeleteActionDeletesProject()
    {
        $this->markTestSkipped('Needs refactoring to new API format');

        $client = $this->getAuthenticatedClient(static::$admin);

        $crawler = $client->request('get', '/projects');
        $totalProjects = $crawler->filter('div.data-list table tbody tr')->count();
        $link = $crawler->filter('div.data-list a:contains("Delete")')->first()->link();
        $client->click($link);

        $crawler = $client->followRedirect();
        $this->assertEquals(--$totalProjects, $crawler->filter('div.data-list table tbody tr')->count());
    }

    /**
     * Tests the deleteAction()
     *
     * Ensures that a 404 response is returned when an invalid token is provided
     *
     * @return void
     */
    public function testDeleteActionReturns404ForInvalidToken()
    {
        $this->markTestSkipped('Needs refactoring to new API format');

        $client = $this->getAuthenticatedClient(static::$admin);

        $crawler = $client->request('get', '/projects');
        $linkHref = $crawler->filter('div.data-list a:contains("Delete")')->first()->attr('href');
        $linkHref .= 'dkwoadkowadawd';

        $client->request('get', $linkHref);
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }
}
