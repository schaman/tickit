<?php

namespace Tickit\ProjectBundle\Tests\Controller;

use Tickit\CoreBundle\Tests\AbstractFunctionalTest;
use Tickit\ProjectBundle\Controller\ProjectController;
use Tickit\ProjectBundle\Entity\Project;

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
     * Tests the createAction()
     *
     * @return void
     */
    public function testCreateActionCreatesProject()
    {
        $client = $this->getAuthenticatedClient(static::$admin);
        $projectName = __FUNCTION__ . time();

        $crawler = $client->request('get', $this->generateRoute('project_create_form'));

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

        $attribute2 = $crawler->filter('input[name="tickit_project[attributes][2][value][]"]:first-child')
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
        $this->assertEquals($this->generateRoute('project_index'), $jsonResponse->returnUrl);

        $doctrine = $this->createClient()->getContainer()->get('doctrine');
        $project  = $doctrine->getRepository('TickitProjectBundle:Project')->findOneByName($projectName);

        $this->assertInstanceOf('\Tickit\ProjectBundle\Entity\Project', $project);
    }

    /**
     * Tests the createAction()
     *
     * @return void
     */
    public function testCreateActionReturnsFormContentForInvalidDetails()
    {
        $client = $this->getAuthenticatedClient(static::$admin);

        $createRoute = $this->generateRoute('project_create_form');
        $crawler = $client->request('get', $createRoute);
        $form = $crawler->selectButton('Save Project')->form(
            array('tickit_project[name]' => '')
        );

        $client->submit($form);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $response = json_decode($client->getResponse()->getContent());
        $this->assertFalse($response->success);
        $this->assertNotEmpty($response->form);
    }

    /**
     * Tests the editAction()
     *
     * @return void
     */
    public function testEditActionUpdatesProjectForValidDetails()
    {
        $client = $this->getAuthenticatedClient(static::$admin);

        $oldProjectName = static::$project->getName();
        $newProjectName = $oldProjectName . ' ' . __FUNCTION__;

        $editRoute = $this->generateRoute('project_edit_form', array('id' => static::$project->getId()));
        $crawler = $client->request('get', $editRoute);

        $form = $crawler->selectButton('Save Changes')->form(
            array('tickit_project[name]' => $newProjectName)
        );
        $client->submit($form);

        $jsonResponse = json_decode($client->getResponse()->getContent());
        $this->assertTrue($jsonResponse->success);

        $doctrine = $this->createClient()->getContainer()->get('doctrine');
        $project  = $doctrine->getRepository('TickitProjectBundle:Project')->find(static::$project->getId());

        $this->assertEquals($newProjectName, $project->getName());
        $this->assertEquals($this->generateRoute('project_index'), $jsonResponse->returnUrl);

        // revert back to old project name for other tests to still pass
        $project->setName($oldProjectName);
        $doctrine->getManager()->flush();
    }

    /**
     * Tests the editAction()
     *
     * @return void
     */
    public function testEditActionInvalidFormSubmission()
    {
        $client = $this->getAuthenticatedClient(static::$admin);

        $editRoute = $this->generateRoute('project_edit_form', array('id' => static::$project->getId()));
        $crawler = $client->request('get', $editRoute);

        $form = $crawler->selectButton('Save Changes')->form(
            array('tickit_project[name]' => '')
        );
        $client->submit($form);

        $jsonResponse = json_decode($client->getResponse()->getContent());
        $this->assertFalse($jsonResponse->success);
        $this->assertNotEmpty($jsonResponse->form);
    }

    /**
     * Tests the editAction()
     *
     * @return void
     */
    public function testEditActionThrows404ForInvalidProjectId()
    {
        $client = $this->getAuthenticatedClient(static::$admin);

        $client->request('post', '/projects/edit/999999999999999999');
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
        $container = $client->getContainer();
        $token = $container->get('form.csrf_provider')->generateCsrfToken(ProjectController::CSRF_DELETE_INTENTION);
        $manager = $container->get('tickit_project.manager');

        $project = new Project();
        $project->setName(__FUNCTION__ . time());
        $manager->create($project);

        $totalProjects = count($manager->getRepository()->findAll());
        $deleteRoute = $this->generateRoute('project_delete', array('id' => $project->getId(), 'token' => $token));

        $client->request('post', $deleteRoute);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $response = json_decode($client->getResponse()->getContent());
        $this->assertTrue($response->success);
        $this->assertEquals(--$totalProjects, count($manager->getRepository()->findAll()));
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

        $deleteRoute = $this->generateRoute(
            'project_delete',
            array('id' => static::$project->getId(), 'token' => 'adwadw32da')
        );

        $client->request('post', $deleteRoute);
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }
}
