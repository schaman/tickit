<?php

namespace Tickit\CoreBundle\Tests\Controller;

use Symfony\Component\Routing\Router;
use Tickit\CoreBundle\Tests\AbstractFunctionalTest;
use Tickit\UserBundle\Entity\User;

/**
 * NavigationController tests.
 *
 * @package Tickit\CoreBundle\Tests\Controller
 * @author  James Halsall <james.t.halsall@googlemail.com>
 * @group   functional
 */
class NavigationController extends AbstractFunctionalTest
{
    /**
     * Array of navigation routes
     *
     * @var array
     */
    protected static $routes;

    /**
     * Sets up the test
     *
     * @return void
     */
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        $routes = array();
        $container = static::createClient()->getContainer();
        /** @var Router $router */
        $router = $container->get('router');

        $routes['dashboard'] = $router->generate('dashboard_index');
        $routes['tickets'] = '#';
        $routes['projects'] = $router->generate('project_index');
        $routes['teams'] = $router->generate('team_index');
        $routes['users'] = $router->generate('user_index');
        $routes['system'] = '';

        static::$routes = $routes;
    }


    /**
     * Tests the topNavigationAction()
     *
     * Makes sure that the correct navigation links are output for an administrator
     *
     * @return void
     */
    public function testTopNavigationActionRendersCorrectLinksForAdmin()
    {
        $client = $this->getAuthenticatedClient(static::$admin);

        $crawler = $client->request('get', '/top-nav');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        foreach (static::$routes as $name => $route) {
            $link = $crawler->filter(sprintf('a[href="%s"]:contains("%s")', $route, ucfirst($name)));
            $this->assertNotEmpty($link);
        }
    }

    /**
     * Tests the subNavigationAction()
     *
     * @todo
     *
     * @return void
     */
    public function testSubNavigationActionRendersCorrectLinks()
    {
        $this->markTestIncomplete();
    }
}
