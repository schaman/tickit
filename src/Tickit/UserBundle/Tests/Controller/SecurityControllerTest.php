<?php

namespace Tickit\UserBundle\Tests\Controller;

use Symfony\Component\HttpFoundation\Response;
use Tickit\CoreBundle\Tests\AbstractUnitTest;
use Tickit\UserBundle\Controller\SecurityController;

/**
 * SecurityController tests
 *
 * @package Tickit\UserBundle\Tests\Controller
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class SecurityControllerTest extends AbstractUnitTest
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
     * Tests the renderLogin() method
     */
    public function testRenderLoginUsesCorrectTemplate()
    {
        $data = [];

        $this->container->expects($this->once())
                        ->method('getParameter')
                        ->with('fos_user.template.engine')
                        ->will($this->returnValue('twig'));

        $templating = $this->getMockTemplateEngine();
        $templating->expects($this->once())
                   ->method('renderResponse')
                   ->with('TickitUserBundle:Security:login.html.twig', $data)
                   ->will($this->returnValue(new Response('login content')));

        $this->container->expects($this->once())
                        ->method('get')
                        ->with('templating')
                        ->will($this->returnValue($templating));

        $controller = new \ReflectionClass($this->getController());
        $method = $controller->getMethod('renderLogin');
        $method->setAccessible(true);
        $response = $method->invoke($this->getController(), $data);

        $this->assertEquals('login content', $response->getContent());
    }

    /**
     * Gets a new controller instance
     *
     * @return SecurityController
     */
    private function getController()
    {
        $controller = new SecurityController();
        $controller->setContainer($this->container);

        return $controller;
    }
}
