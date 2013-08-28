<?php

namespace Tickit\TeamBundle\Tests\Controller;

use Tickit\CoreBundle\Tests\AbstractFunctionalTest;

/**
 * ApiController tests
 *
 * @package Tickit\TeamBundle\Tests\Controller
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class ApiControllerTest extends AbstractFunctionalTest
{
    /**
     * Tests the listAction() method
     *
     * @return void
     */
    public function testListActionDisplaysCorrectNumberOfTeams()
    {
        $client = $this->getAuthenticatedClient(static::$admin);
        $doctrine = $client->getContainer()->get('doctrine');
        $route = $this->generateRoute('api_team_list');

        $client->request('get', $route);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $response = json_decode($client->getResponse()->getContent());

        $this->assertInternalType('array', $response);
        $totalTeams = count($doctrine->getRepository('TickitTeamBundle:Team')->findAll());

        $this->assertCount($totalTeams, $response);
    }
}
