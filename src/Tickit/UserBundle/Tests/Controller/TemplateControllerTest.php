<?php

namespace Tickit\UserBundle\Tests\Controller;

use Symfony\Component\HttpFoundation\Response;
use Tickit\CoreBundle\Tests\AbstractUnitTest;
use Tickit\UserBundle\Controller\TemplateController;
use Tickit\UserBundle\Entity\User;

/**
 * TemplateController tests
 *
 * @package Tickit\UserBundle\Tests\Controller
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class TemplateControllerTest extends AbstractUnitTest
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $userManager;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $formHelper;

    /**
     * Setup
     */
    protected function setUp()
    {
        $this->userManager = $this->getMockBuilder('\Tickit\UserBundle\Manager\UserManager')
                                  ->disableOriginalConstructor()
                                  ->getMock();

        $this->formHelper = $this->getMockFormHelper();
    }
    
    /**
     * Tests the createFormAction() method
     */
    public function testCreateFormActionBuildsCorrectResponse()
    {
        $user = new User();

        $this->userManager->expects($this->once())
                          ->method('createUser')
                          ->will($this->returnValue($user));

        $form = $this->getMockForm();
        $this->formHelper->expects($this->once())
                         ->method('createForm')
                         ->with('tickit_user', $user)
                         ->will($this->returnValue($form));

        $this->formHelper->expects($this->once())
                         ->method('renderForm')
                         ->with('TickitUserBundle:User:create.html.twig', $form)
                         ->will($this->returnValue(new Response('form content')));

        $response = $this->getController()->createFormAction();
        $this->assertEquals('form content', $response->getContent());
    }

    /**
     * Tests the editFormAction() method
     */
    public function testEditFormActionBuildsCorrectResponse()
    {
        $user = new User();

        $form = $this->getMockForm();
        $this->formHelper->expects($this->once())
                         ->method('createForm')
                         ->with('tickit_user', $user)
                         ->will($this->returnValue($form));

        $this->formHelper->expects($this->once())
                         ->method('renderForm')
                         ->with('TickitUserBundle:User:edit.html.twig', $form)
                         ->will($this->returnValue(new Response('form content')));

        $response = $this->getController()->editFormAction($user);
        $this->assertEquals('form content', $response->getContent());
    }

    /**
     * Gets a new controller instance
     *
     * @return TemplateController
     */
    private function getController()
    {
        return new TemplateController($this->userManager, $this->formHelper);
    }
}
