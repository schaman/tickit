<?php

namespace Tickit\ProjectBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Tests for the ProjectController
 *
 * @package Tickit\ProjectBundle\Tests\Controller
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class ProjectControllerTest extends WebTestCase
{
    /**
     * Ensures that the index action displays projects correctly
     *
     * @return void
     */
    public function testProjectActionsAreBehindFirewall()
    {
        $client = static::createClient();
        $router = $client->getContainer()->get('router');
        $client->followRedirects();

        $crawler = $client->request('get', $router->generate('project_index'));

        $this->assertEquals('Login', $crawler->filter('h2')->text(), '<h2> contains correct content');
    }
}
