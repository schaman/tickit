<?php

namespace Tickit\ClientBundle\Tests\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Tickit\ClientBundle\Controller\ClientController;
use Tickit\ClientBundle\Entity\Client;
use Tickit\CoreBundle\Tests\AbstractUnitTest;

/**
 * ClientController tests
 *
 * @package Tickit\ClientBundle\Tests\Controller
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class ClientControllerTest extends AbstractUnitTest
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $formHelper;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $baseHelper;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $csrfHelper;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $clientManager;

    /**
     * Setup
     */
    protected function setUp()
    {
        $this->formHelper = $this->getMockFormHelper();
        $this->baseHelper = $this->getMockBaseHelper();
        $this->csrfHelper = $this->getMockCsrfHelper();

        $this->clientManager = $this->getMockBuilder('\Tickit\ClientBundle\Manager\ClientManager')
                                    ->disableOriginalConstructor()
                                    ->getMock();
    }
    
    /**
     * Tests the createAction() method
     */
    public function testCreateActionCreatesClientForValidForm()
    {
        $client = new Client();

        $form = $this->getMockForm();
        $this->trainFormHelperToCreateForm('tickit_client', $client, $form);

        $request = new Request();
        $this->trainBaseHelperToReturnRequest($request);
        $this->trainFormToHandleRequest($form, $request);
        $this->trainFormToBeValid($form);
        $this->trainFormToReturnClient($form, $client);
        $this->clientManager->expects($this->once())
                            ->method('create')
                            ->with($client);

        $expectedData = ['success' => true];
        $response = $this->getController()->createAction();
        $this->assertEquals($expectedData, json_decode($response->getContent(), true));
    }

    /**
     * Tests the createAction() method
     */
    public function testCreateActionHandlesInvalidForm()
    {
        $client = new Client();

        $form = $this->getMockForm();
        $this->trainFormHelperToCreateForm('tickit_client', $client, $form);

        $request = new Request();
        $this->trainBaseHelperToReturnRequest($request);
        $this->trainFormToHandleRequest($form, $request);
        $this->trainFormToBeInvalid($form);
        $this->clientManager->expects($this->never())
                            ->method('create');

        $this->formHelper->expects($this->once())
                         ->method('renderForm')
                         ->with('TickitClientBundle:Client:create.html.twig', $form)
                         ->will($this->returnValue(new Response('form-content')));

        $expectedData = ['success' => false, 'form' => 'form-content'];
        $response = $this->getController()->createAction();

        $this->assertEquals($expectedData, json_decode($response->getContent(), true));
    }

    /**
     * Tests the editAction() method
     */
    public function testEditActionUpdatesClientForValidForm()
    {
        $client = new Client();
        $client->setId(1);

        $form = $this->getMockForm();
        $this->trainFormHelperToCreateForm('tickit_client', $client, $form);

        $request = new Request();
        $this->trainBaseHelperToReturnRequest($request);
        $this->trainFormToHandleRequest($form, $request);
        $this->trainFormToBeValid($form);
        $this->trainFormToReturnClient($form, $client);

        $this->clientManager->expects($this->once())
                            ->method('update')
                            ->with($client);

        $expectedData = ['success' => true];
        $response = $this->getController()->editAction($client);

        $this->assertEquals($expectedData, json_decode($response->getContent(), true));
    }

    /**
     * Tests the editAction() method
     */
    public function testEditActionHandlesInvalidForm()
    {
        $client = new Client();
        $client->setId(1);

        $form = $this->getMockForm();
        $this->trainFormHelperToCreateForm('tickit_client', $client, $form);

        $request = new Request();
        $this->trainBaseHelperToReturnRequest($request);
        $this->trainFormToHandleRequest($form, $request);
        $this->trainFormToBeInvalid($form);

        $this->clientManager->expects($this->never())
                            ->method('update');

        $this->formHelper->expects($this->once())
                         ->method('renderForm')
                         ->with('TickitClientBundle:Client:edit.html.twig', $form)
                         ->will($this->returnValue(new Response('form-content')));

        $expectedData = ['success' => false, 'form' => 'form-content'];
        $response = $this->getController()->editAction($client);

        $this->assertEquals($expectedData, json_decode($response->getContent(), true));
    }

    /**
     * Gets a new controller instance
     *
     * @return ClientController
     */
    private function getController()
    {
        return new ClientController($this->formHelper, $this->baseHelper, $this->csrfHelper, $this->clientManager);
    }

    private function trainBaseHelperToReturnRequest(Request $request)
    {
        $this->baseHelper->expects($this->once())
                         ->method('getRequest')
                         ->will($this->returnValue($request));
    }

    private function trainFormToHandleRequest(\PHPUnit_Framework_MockObject_MockObject $form, Request $request)
    {
        $form->expects($this->once())
             ->method('handleRequest')
             ->with($request);
    }

    private function trainFormToBeValid(\PHPUnit_Framework_MockObject_MockObject $form)
    {
        $form->expects($this->once())
             ->method('isValid')
             ->will($this->returnValue(true));
    }

    private function trainFormToBeInvalid(\PHPUnit_Framework_MockObject_MockObject $form)
    {
        $form->expects($this->once())
             ->method('isValid')
             ->will($this->returnValue(false));
    }

    private function trainFormToReturnClient(\PHPUnit_Framework_MockObject_MockObject $form, Client $client)
    {
        $form->expects($this->once())
             ->method('getData')
             ->will($this->returnValue($client));

    }

    private function trainFormHelperToCreateForm($type, $data, $returnedForm)
    {
        $this->formHelper->expects($this->once())
                         ->method('createForm')
                         ->with($type, $data)
                         ->will($this->returnValue($returnedForm));
    }
}
