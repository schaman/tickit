<?php

namespace Tickit\UserBundle\Tests\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Tickit\CoreBundle\Tests\AbstractFunctionalTest;

/**
 * ProfileController tests
 *
 * @package Tickit\UserBundle\Tests\Controller
 * @author  James Halsall <james.t.halsall@googlemail.com>
 * @group   functional
 */
class ProfileControllerTest extends AbstractFunctionalTest
{
    /**
     * Tests the showAction()
     *
     * @return void
     */
    public function testShowActionRedirectsToCorrectRoute()
    {
        $client = $this->getAuthenticatedClient(static::$developer);
        $container = $client->getContainer();
        $router = $container->get('router');

        $client->request('get', $router->generate('fos_user_profile_show'));
        /** @var RedirectResponse $response  */
        $response = $client->getResponse();
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals($router->generate('fos_user_profile_edit'), $response->getTargetUrl());
    }
}
