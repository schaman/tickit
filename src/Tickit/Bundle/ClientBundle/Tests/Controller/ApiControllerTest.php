<?php

/*
 * Tickit, an open source web based bug management tool.
 *
 * Copyright (C) 2014  Tickit Project <http://tickit.io>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Tickit\Bundle\ClientBundle\Tests\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Csrf\CsrfToken;
use Tickit\Bundle\ClientBundle\Controller\ApiController;
use Tickit\Bundle\ClientBundle\Controller\ClientController;
use Tickit\Bundle\ClientBundle\Form\Type\FilterFormType;
use Tickit\Component\Filter\Map\Client\ClientFilterMapper;
use Tickit\Component\Model\Client\Client;
use Tickit\Component\Filter\Collection\FilterCollection;
use Tickit\Component\Test\AbstractUnitTest;

/**
 * ApiController tests
 *
 * @package Tickit\Bundle\ClientBundle\Tests\Controller
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
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $paginator;

    /**
     * Setup
     */
    protected function setUp()
    {
        $this->filterBuilder = $this->getMockFilterCollectionBuilder();
        $this->baseHelper = $this->getMockBaseHelper();
        $this->csrfHelper = $this->getMockCsrfHelper();

        $this->clientRepo = $this->getMockBuilder('\Tickit\Bundle\ClientBundle\Doctrine\Repository\ClientRepository')
                                 ->disableOriginalConstructor()
                                 ->getMock();

        $this->paginator = $this->getMockPaginator();
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
                            ->with($request, FilterFormType::NAME, new ClientFilterMapper())
                            ->will($this->returnValue($filters));

        $clients = [new Client(), new Client()];
        $this->trainPaginatorToReturnIterator($clients);
        $this->trainPaginatorToReturnCount(2);

        $this->clientRepo->expects($this->once())
                         ->method('findByFilters')
                         ->with($filters)
                         ->will($this->returnValue($this->paginator));

        $this->csrfHelper->expects($this->once())
                         ->method('generateCsrfToken', ClientController::CSRF_DELETE_INTENTION)
                         ->will($this->returnValue(new CsrfToken('id', 'csrf-token-value')));

        $expectedData = ['data' => [['client'], ['client']], 'total' => 2, 'pages' => 1, 'currentPage' => 1];
        $decorator = $this->getMockObjectCollectionDecorator();
        $this->trainBaseHelperToReturnObjectCollectionDecorator($decorator);
        $this->trainObjectCollectionDecoratorToExpectClientCollection($decorator, new \ArrayIterator($clients), $expectedData['data']);

        $response = $this->getController()->listAction(1);
        $this->assertEquals($expectedData, json_decode($response->getContent(), true));
    }

    /**
     * Gets a new controller instance
     *
     * @return ApiController
     */
    private function getController()
    {
        return new ApiController($this->filterBuilder, $this->baseHelper, $this->csrfHelper, $this->clientRepo);
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
        $clients,
        array $returnData
    ) {
        $objectDecorator->expects($this->once())
                        ->method('decorate')
                        ->with(
                            $clients,
                            ['id', 'name', 'url', 'status', 'totalProjects', 'createdAt'],
                            ['csrfToken' => 'csrf-token-value']
                        )
                        ->will($this->returnValue($returnData));
    }

    private function trainPaginatorToReturnIterator(array $data)
    {
        $this->paginator->expects($this->once())
                        ->method('getIterator')
                        ->will($this->returnValue(new \ArrayIterator($data)));
    }

    private function trainPaginatorToReturnCount($count)
    {
        $this->paginator->expects($this->once())
                        ->method('count')
                        ->will($this->returnValue($count));
    }
}
