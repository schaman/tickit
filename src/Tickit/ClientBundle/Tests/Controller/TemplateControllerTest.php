<?php

namespace Tickit\ClientBundle\Tests\Controller;

use Symfony\Component\HttpFoundation\Response;
use Tickit\ClientBundle\Controller\TemplateController;
use Tickit\ClientBundle\Entity\Client;
use Tickit\CoreBundle\Tests\AbstractUnitTest;

/**
 * TemplateController tests
 *
 * @package Tickit\ClientBundle\Tests\Controller
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class TemplateControllerTest extends AbstractUnitTest
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $formHelper;

    /**
     * Setup
     */
    protected function setUp()
    {
        $this->formHelper = $this->getMockFormHelper();
    }
    
    /**
     * Tests the createClientFormAction() method
     */
    public function testCreateClientFormActionBuildsCorrectResponse()
    {
        $client = new Client();

        $form = $this->getMockForm();
        $this->trainFormHelperToCreateForm($client, $form);
        $this->trainFormHelperToRenderForm($form, 'TickitClientBundle:Client:create.html.twig', 'create-form-template');

        $this->assertEquals('create-form-template', $this->getController()->createClientFormAction()->getContent());
    }

    /**
     * Tests the editClientFormAction() method
     */
    public function testEditClientFormActionBuildsCorrectResponse()
    {
        $client = new Client();
        $client->setId(1);

        $form = $this->getMockForm();
        $this->trainFormHelperToCreateForm($client, $form);
        $this->trainFormHelperToRenderForm($form, 'TickitClientBundle:Client:edit.html.twig', 'edit-form-template');

        $this->assertEquals('edit-form-template', $this->getController()->editClientFormAction($client)->getContent());
    }

    /**
     * Gets a new controller instance
     *
     * @return TemplateController
     */
    private function getController()
    {
        return new TemplateController($this->formHelper);
    }

    private function trainFormHelperToCreateForm(Client $client, \PHPUnit_Framework_MockObject_MockObject $form)
    {
        $this->formHelper->expects($this->once())
             ->method('createForm')
             ->with('tickit_client', $client)
             ->will($this->returnValue($form));
    }

    private function trainFormHelperToRenderForm(\PHPUnit_Framework_MockObject_MockObject $form, $template, $returnText)
    {
        $this->formHelper->expects($this->once())
            ->method('renderForm')
            ->with($template, $form)
            ->will($this->returnValue(new Response($returnText)));
    }
}
