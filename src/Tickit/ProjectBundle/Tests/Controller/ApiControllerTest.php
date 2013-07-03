<?php

namespace Tickit\ProjectBundle\Tests\Controller;

use Tickit\CoreBundle\Tests\AbstractFunctionalTest;
use Tickit\ProjectBundle\Manager\ProjectManager;

/**
 * Description
 *
 * @package Namespace\Class
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class ApiControllerTest extends AbstractFunctionalTest
{
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

        $client->request('get', $this->generateRoute('api_project_list'));
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        /** @var ProjectManager $projectManager */
        $projectManager = $client->getContainer()->get('tickit_project.manager');
        $repository = $projectManager->getRepository();
        $totalProjects = count($repository->findAll());

        $response = json_decode($client->getResponse()->getContent());
        $this->assertInternalType('array', $response);

        $this->assertCount($totalProjects, $response);
    }
}
