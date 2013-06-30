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
 * @group   functional
 */
class ProjectControllerTest extends AbstractFunctionalTest
{
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
        $crawler = $client->followRedirect();
        $this->assertEquals('Login', $crawler->filter('h2')->text(), '<h2> contains correct content');
    }

    /**
     * Tests the addAction()
     *
     * Ensures that the addAction() creates a project with valid details
     *
     * @return void
     */
    public function testAddActionCreatesProject()
    {
        $client = $this->getAuthenticatedClient(static::$admin);

        $crawler = $client->request('get', '/projects');
        $totalProjects = $crawler->filter('div.data-list table tbody tr')->count();

        $crawler = $client->request('get', '/projects/create');
        $form = $crawler->selectButton('Save Project')->form(
            array(
                'tickit_project[name]' => 'Valid Project Name'
            )
        );
        $client->submit($form);
        $crawler = $client->followRedirect();
        $this->assertGreaterThan(
            0,
            $crawler->filter('div.flash-notice:contains("The project has been created successfully")')->count()
        );
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

        $crawler = $client->request('get', '/projects');
        $currentProjectName = $crawler->filter('div.data-list tbody tr td')->eq(1)->text();
        $link = $crawler->filter('div.data-list a:contains("Edit")')->first()->link();
        $crawler = $client->click($link);

        $newProjectName = strrev($currentProjectName);
        $form = $crawler->selectButton('Save Changes')->form();
        $crawler = $client->submit($form, array('tickit_project[name]' => $newProjectName));
        $this->assertGreaterThan(
            0,
            $crawler->filter('div.flash-notice:contains("The project has been updated successfully")')->count()
        );
        $this->assertEquals($newProjectName, $crawler->filter('input[name="tickit_project[name]"]')->attr('value'));
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
        $client = $this->getAuthenticatedClient(static::$admin);

        $crawler = $client->request('get', '/projects');
        $totalProjects = $crawler->filter('div.data-list table tbody tr')->count();
        $link = $crawler->filter('div.data-list a:contains("Delete")')->first()->link();
        $client->click($link);

        $crawler = $client->followRedirect();
        $this->assertGreaterThan(
            0,
            $crawler->filter('div.flash-notice:contains("The project has been successfully deleted")')->count()
        );
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
        $client = $this->getAuthenticatedClient(static::$admin);

        $crawler = $client->request('get', '/projects');
        $linkHref = $crawler->filter('div.data-list a:contains("Delete")')->first()->attr('href');
        $linkHref .= 'dkwoadkowadawd';

        $client->request('get', $linkHref);
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }
}
