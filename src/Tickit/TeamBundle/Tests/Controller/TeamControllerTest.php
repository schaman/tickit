<?php

namespace Tickit\TeamBundle\Tests\Controller;
use Tickit\CoreBundle\Tests\AbstractFunctionalTest;
use Tickit\TeamBundle\Controller\TeamController;
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

    /**
     * Tests the editTeamAction() method
     *
     * @return void
     */
    public function testEditTeamActionReturnsFormContentForInvalidDetails()
    {
        $client = $this->getAuthenticatedClient(static::$admin);
        $doctrine = $client->getContainer()->get('doctrine');
        $route = $this->generateRoute('team_edit_form', array('id' => static::$team->getId()));
        $crawler = $client->request('get', $route);

        $form = $crawler->selectButton('Save Changes')->form(
            array('tickit_team[name]' => '')
        );
        $client->submit($form);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $response = json_decode($client->getResponse()->getContent());
        $this->assertFalse($response->success);
        $this->assertNotEmpty($response->form);

        $nonEditedTeam = $doctrine->getRepository('TickitTeamBundle:Team')->find(static::$team->getId());
        $this->assertEquals($nonEditedTeam->getName(), static::$team->getName());
    }

    /**
     * Tests the editTeamAction() method
     *
     * @return void
     */
    public function testEditTeamActionUpdatesTeamForValidDetails()
    {
        $client = $this->getAuthenticatedClient(static::$admin);
        $container = $client->getContainer();
        $doctrine = $container->get('doctrine');

        $newName = __FUNCTION__ . uniqid();
        $newTeam = new Team();
        $newTeam->setName($newName);
        $container->get('tickit_team.manager')->create($newTeam);

        $route = $this->generateRoute('team_edit_form', array('id' => $newTeam->getId()));
        $crawler = $client->request('get', $route);

        $form = $crawler->selectButton('Save Changes')->form(
            array('tickit_team[name]' => $newName . '_updated')
        );
        $client->submit($form);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $response = json_decode($client->getResponse()->getContent());
        $this->assertTrue($response->success);

        $updatedTeam = $doctrine->getRepository('TickitTeamBundle:Team')->find($newTeam->getId());
        $doctrine->getManager()->refresh($updatedTeam);

        $this->assertEquals($newName . '_updated', $updatedTeam->getName());
    }

    /**
     * Tests the deleteTeamAction() method
     *
     * @return void
     */
    public function testDeleteTeamActionThrows404ForInvalidToken()
    {
        $client = $this->getAuthenticatedClient(static::$admin);
        $route = $this->generateRoute(
            'team_delete',
            array('id' => static::$team->getId(), 'token' => 'adkowagjiwaga')
        );

        $client->request('post', $route);
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }

    /**
     * Tests the deleteTeamAction() method
     *
     * @return void
     */
    public function testDeleteTeamActionDeletesTeam()
    {
        $client = $this->getAuthenticatedClient(static::$admin);
        $container = $client->getContainer();
        $token = $container->get('form.csrf_provider')->generateCsrfToken(TeamController::CSRF_DELETE_INTENTION);
        $manager = $container->get('tickit_team.manager');

        $team = new Team();
        $team->setName(__FUNCTION__ . time());
        $manager->create($team);

        $totalTeams = count($manager->getRepository()->findAll());
        $route = $this->generateRoute('team_delete', array('id' => $team->getId(), 'token' => $token));

        $client->request('post', $route);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $response = json_decode($client->getResponse()->getContent());
        $this->assertTrue($response->success);
        $this->assertEquals(--$totalTeams, count($manager->getRepository()->findAll()));
    }
}
