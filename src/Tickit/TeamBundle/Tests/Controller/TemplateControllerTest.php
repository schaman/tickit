<?php

namespace Tickit\TeamBundle\Tests\Controller;

use Tickit\CoreBundle\Tests\AbstractFunctionalTest;

/**
 * TemplateController tests.
 *
 * @package Tickit\TeamBundle\Tests\Controller
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class TemplateControllerTest extends AbstractFunctionalTest
{
    /**
     * Tests the createTeamFormAction() method
     *
     * @return void
     */
    public function testCreateTeamFormActionServesCorrectMarkup()
    {
        $client = $this->getAuthenticatedClient(static::$admin);
        $route = $this->generateRoute('team_create_form');

        $crawler = $client->request('get', $route);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals($this->generateRoute('team_create'), $crawler->filter('form')->attr('action'));
    }

    /**
     * Tests the editTeamFormAction() method
     *
     * @return void
     */
    public function testEditTeamFormActionServesCorrectMarkup()
    {
        $client = $this->getAuthenticatedClient(static::$admin);
        $doctrine = $client->getContainer()->get('doctrine');
        $team = $doctrine->getRepository('TickitTeamBundle:Team')->findOneByName('Test Development Team');
        $route = $this->generateRoute('team_edit_form', array('id' => $team->getId()));

        $crawler = $client->request('get', $route);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $expectedRoute = $this->generateRoute('team_edit', array('id' => $team->getId()));
        $this->assertEquals($expectedRoute, $crawler->filter('form')->attr('action'));
    }
}
