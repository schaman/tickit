<?php

namespace Tickit\UserBundle\Tests\Controller;

use Tickit\CoreBundle\Tests\AbstractUnitTest;
use Tickit\UserBundle\Controller\ProfileController;

/**
 * ProfileController tests
 *
 * @package Tickit\UserBundle\Tests\Controller
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class ProfileControllerTest extends AbstractUnitTest
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $container;

    /**
     * Setup
     */
    protected function setUp()
    {
        $this->container = $this->getMockContainer();
    }

    /**
     * Tests the showAction()
     */
    public function testShowActionRedirectsToEditAction()
    {
        $router = $this->getMockRouter();

        $this->container->expects($this->once())
                        ->method('get')
                        ->with('router')
                        ->will($this->returnValue($router));

        $router->expects($this->once())
               ->method('generate')
               ->with('fos_user_profile_edit')
               ->will($this->returnValue('/profile/url'));

        $response = $this->getController()->showAction();
        $this->assertInstanceOf('\Symfony\Component\HttpFoundation\RedirectResponse', $response);
    }

    /**
     * Gets a controller instance
     */
    private function getController()
    {
        $controller = new ProfileController();
        $controller->setContainer($this->container);

        return $controller;
    }
}
