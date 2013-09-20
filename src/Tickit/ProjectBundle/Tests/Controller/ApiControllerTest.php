<?php

namespace Tickit\ProjectBundle\Tests\Controller;

use Tickit\CoreBundle\Tests\AbstractFunctionalTest;
use Tickit\ProjectBundle\Manager\AttributeManager;
use Tickit\ProjectBundle\Manager\ProjectManager;

/**
 * ApiController tests
 *
 * @package Tickit\ProjectBundle\Tests\Controller
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class ApiControllerTest extends AbstractFunctionalTest
{
    /**
     * Tests the listAction() method
     *
     * @return void
     */
    public function testListActionDisplaysCorrectNumberOfProjects()
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

        $first = array_shift($response);
        $this->assertTrue(isset($first->csrf_token));
    }

    /**
     * Tests the listAction() method
     *
     * @return void
     */
    public function testAttributesListActionDisplaysCorrectNumberOfAttributes()
    {
        $client = $this->getAuthenticatedClient(static::$admin);

        $client->request('get', $this->generateRoute('api_project_attribute_list'));
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        /** @var AttributeManager $manager */
        $manager = $client->getContainer()->get('tickit_project.attribute_manager');
        $total = count($manager->getRepository()->findAll());

        $response = json_decode($client->getResponse()->getContent());
        $this->assertInternalType('array', $response);

        $this->assertCount($total, $response);
    }
}
