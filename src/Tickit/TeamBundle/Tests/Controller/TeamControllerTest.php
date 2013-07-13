<?php

namespace Tickit\TeamBundle\Tests\Controller;
use Tickit\CoreBundle\Tests\AbstractFunctionalTest;
use Tickit\TeamBundle\Entity\Team;

/**
 * TeamController tests
 *
 * @package Tickit\TeamBundle\Tests\Controller
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class TeamControllerTest extends AbstractFunctionalTest
{
    /**
     * Sample team entity
     *
     * @var Team
     */
    protected static $team;

    /**
     * {@inheritDoc}
     *
     * @return void
     */
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        $doctrine = static::createClient()->getContainer()->get('doctrine');

        static::$team = $doctrine->getRepository('TickitTeamBundle:Team')
                                    ->findOneByName('Test Team 1');
    }

    /**
     * Tests the createTeamAction() method
     *
     * @return void
     */
    public function testCreateTeamActionReturnsFormContentForInvalidDetails()
    {
        $client = $this->getAuthenticatedClient(static::$admin);
        $route = $this->generateRoute('team_create_form');
        $crawler = $client->request('get', $route);

        $form = $crawler->selectButton('Save Team')->form(
            array('tickit_team[name]' => '')
        );
        $client->submit($form);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $response = json_decode($client->getResponse()->getContent());
        $this->assertFalse($response->success);
        $this->assertNotEmpty($response->form);
    }

    /**
     * Test the createTeamAction() method
     *
     * @return void
     */
    public function testCreateTeamActionCreatesTeamForValidDetails()
    {
        $client = $this->getAuthenticatedClient(static::$admin);
        $doctrine = $client->getContainer()->get('doctrine');
        $route = $this->generateRoute('team_create_form');
        $crawler=  $client->request('get', $route);

        $teamName = __FUNCTION__ . time();
        $form = $crawler->selectButton('Save Team')->form(
            array('tickit_team[name]' => $teamName)
        );
        $client->submit($form);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $response = json_decode($client->getResponse()->getContent());
        $this->assertTrue($response->success);

        $team = $doctrine->getRepository('TickitTeamBundle:Team')->findOneByName($teamName);
        $this->assertInstanceOf('\Tickit\TeamBundle\Entity\Team', $team);
    }
}
