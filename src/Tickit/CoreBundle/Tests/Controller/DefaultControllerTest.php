<?php

namespace Tickit\CoreBundle\Tests\Controller;

use Symfony\Component\HttpFoundation\Response;
use Tickit\CoreBundle\Controller\DefaultController;
use Tickit\CoreBundle\Tests\AbstractUnitTest;

/**
 * DefaultController tests
 *
 * @package Tickit\CoreBundle\Tests\Controller
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class DefaultControllerTest extends AbstractUnitTest
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $templateEngine;

    /**
     * Setup
     */
    protected function setUp()
    {
        $this->templateEngine = $this->getMockTemplateEngine();
    }
    
    /**
     * Tests the defaultAction() method
     */
    public function testDefaultActionRendersCorrectTemplate()
    {
        $expectedResponse = new Response();

        $this->templateEngine->expects($this->once())
                             ->method('renderResponse')
                             ->with('::base.html.twig')
                             ->will($this->returnValue($expectedResponse));

        $response = $this->getController()->defaultAction();
        $this->assertSame($expectedResponse, $response);
    }

    /**
     * Gets a new controller
     *
     * @return DefaultController
     */
    private function getController()
    {
        return new DefaultController($this->templateEngine);
    }
}
