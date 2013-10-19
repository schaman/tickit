<?php

namespace Tickit\ClientBundle\Tests\Controller;

use Symfony\Component\HttpFoundation\Request;
use Tickit\ClientBundle\Controller\ApiController;
use Tickit\ClientBundle\Controller\ClientController;
use Tickit\ClientBundle\Entity\Client;
use Tickit\CoreBundle\Filters\Collection\FilterCollection;
use Tickit\CoreBundle\Tests\AbstractUnitTest;

/**
 * ApiController tests
 *
 * @package Tickit\ClientBundle\Tests\Controller
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class ApiControllerTest extends AbstractUnitTest
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $filterBuilder;

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
    private $clientRepo;

    /**
     * Setup
     */
    protected function setUp()
    {
        $this->filterBuilder = $this->getMockFilterCollectionBuilder();
        $this->baseHelper = $this->getMockBaseHelper();
        $this->csrfHelper = $this->getMockCsrfHelper();

        $this->clientRepo = $this->getMockBuilder('\Tickit\ClientBundle\Entity\Repository\ClientRepository')
                                 ->disableOriginalConstructor()
                                 ->getMock();
    }
    
    /**
     * Tests the listAction() method
     */
    public function testListActionBuildsCorrectResponse()
    {
        $request = new Request();
        $filters = new FilterCollection();
        $this->trainBaseHelperToReturnRequest($request);

        $this->filterBuilder->expects($this->once())
                            ->method('buildFromRequest')
                            ->with($request)
                            ->will($this->returnValue($filters));

        $clients = [new Client(), new Client()];

        $this->clientRepo->expects($this->once())
                         ->method('findByFilters')
                         ->with($filters)
                         ->will($this->returnValue($clients));

        $this->csrfHelper->expects($this->once())
                         ->method('generateCsrfToken', ClientController::CSRF_DELETE_INTENTION)
                         ->will($this->returnValue('csrf-token-value'));

        $expectedData = [['client'], ['client']];
        $decorator = $this->getMockObjectCollectionDecorator();
        $this->trainBaseHelperToReturnObjectCollectionDecorator($decorator);
        $this->trainObjectCollectionDecoratorToExpectClientCollection($decorator, $clients, $expectedData);

        $response = $this->getController()->listAction();
        $this->assertEquals($expectedData, json_decode($response->getContent(), true));
    }

    /**
     * Gets a new controller instance
     *
     * @return ApiController
     */
    private function getController()
    {
        return new ApiController($this->filterBuilder, $this->baseHelper,$this->csrfHelper, $this->clientRepo);
    }

    private function trainBaseHelperToReturnRequest(Request $request)
    {
        $this->baseHelper->expects($this->once())
                         ->method('getRequest')
                         ->will($this->returnValue($request));
    }

    private function trainBaseHelperToReturnObjectCollectionDecorator(\PHPUnit_Framework_MockObject_MockObject $decorator)
    {
        $this->baseHelper->expects($this->once())
                         ->method('getObjectCollectionDecorator')
                         ->will($this->returnValue($decorator));
    }

    private function trainObjectCollectionDecoratorToExpectClientCollection(
        \PHPUnit_Framework_MockObject_MockObject $objectDecorator,
        array $clients,
        array $returnData
    ) {
        $objectDecorator->expects($this->once())
                        ->method('decorate')
                        ->with(
                            $clients,
                            ['id', 'name', 'url', 'created'],
                            ['csrf_token' => 'csrf-token-value']
                        )
                        ->will($this->returnValue($returnData));
    }
}
