<?php

namespace Tickit\UserBundle\Tests\Controller;

use Tickit\CoreBundle\Tests\AbstractFunctionalTest;
use Tickit\UserBundle\Manager\UserManager;

/**
 * UserController tests
 *
 * @package Tickit\UserBundle\Tests\Controller
 * @author  James Halsall <james.t.halsall@googlemail.com>
 * @group   functional
 */
class UserControllerTest extends AbstractFunctionalTest
{
    /**
     * Ensures that the user actions are not publicly accessible
     *
     * @return void
     */
    public function testUserActionsAreBehindFirewall()
    {
        $client = static::createClient();
        $router = $client->getContainer()->get('router');

        $client->request('get', $router->generate('user_index'));
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $crawler = $client->followRedirect();
        $this->assertEquals('Login', $crawler->filter('h2')->text());
    }

    /**
     * Tests the indexAction()
     *
     * Ensures that any layout components are as we expect
     */
    public function testIndexActionLayout()
    {
        $client = $this->getAuthenticatedClient(static::$admin);
        $router = $client->getContainer()->get('router');
        /** @var UserManager $manager */
        $manager = $client->getContainer()->get('tickit_user.manager');
        $totalUsers = count($manager->getRepository()->findAll());

        $crawler = $client->request('get', $router->generate('user_index'));
        $this->assertEquals($totalUsers, $crawler->filter('div.data-list tbody tr')->count());
        $this->assertEquals('Manage Users', $crawler->filter('h2')->text());
    }
}