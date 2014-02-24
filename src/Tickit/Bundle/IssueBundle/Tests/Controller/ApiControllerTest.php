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

namespace Tickit\Bundle\IssueBundle\Tests\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Csrf\CsrfToken;
use Tickit\Bundle\IssueBundle\Controller\ApiController;
use Tickit\Bundle\IssueBundle\Controller\IssueController;
use Tickit\Bundle\IssueBundle\Form\Type\FilterFormType;
use Tickit\Component\Filter\Collection\FilterCollection;
use Tickit\Component\Filter\Map\Issue\IssueFilterMapper;
use Tickit\Component\Model\Issue\Issue;
use Tickit\Component\Test\AbstractUnitTest;

/**
 * ApiController tests
 *
 * @package Tickit\Bundle\IssueBundle\Tests\Controller
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
    private $issueRepository;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $paginator;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $formHelper;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $form;

    /**
     * Setup
     */
    protected function setUp()
    {
        $this->filterBuilder = $this->getMockFilterCollectionBuilder();
        $this->baseHelper = $this->getMockBaseHelper();
        $this->csrfHelper = $this->getMockCsrfHelper();
        $this->issueRepository = $this->getMockBuilder('\Tickit\Bundle\IssueBundle\Doctrine\Repository\IssueRepository')
                                      ->disableOriginalConstructor()
                                      ->getMock();
        $this->formHelper = $this->getMockFormHelper();
        $this->paginator = $this->getMockPaginator();
        $this->form = $this->getMockForm();
    }

    /**
     * Tests the listAction() method
     */
    public function testListActionBuildsCorrectResponse()
    {
        $request = new Request();
        $filterData = [
            'key' => 'value'
        ];
        $filters = new FilterCollection();
        $this->trainBaseHelperToReturnRequest($request);

        $this->formHelper->expects($this->once())
                         ->method('createForm')
                         ->with(new FilterFormType(), null)
                         ->will($this->returnValue($this->form));

        $this->form->expects($this->once())
                   ->method('getData')
                   ->will($this->returnValue($filterData));

        $this->filterBuilder->expects($this->once())
                            ->method('buildFromArray')
                            ->with($filterData, new IssueFilterMapper())
                            ->will($this->returnValue($filters));

        $clients = [new Issue(), new Issue()];
        $this->trainPaginatorToReturnIterator($clients);
        $this->trainPaginatorToReturnCount(2);

        $this->issueRepository->expects($this->once())
                              ->method('findByFilters')
                              ->with($filters)
                              ->will($this->returnValue($this->paginator));

        $this->csrfHelper->expects($this->once())
                         ->method('generateCsrfToken', IssueController::CSRF_DELETE_INTENTION)
                         ->will($this->returnValue(new CsrfToken('id', 'csrf-token-value')));

        $expectedData = ['data' => [['issue'], ['issue']], 'total' => 2, 'pages' => 1, 'currentPage' => 1];
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
        return new ApiController(
            $this->filterBuilder,
            $this->baseHelper,
            $this->csrfHelper,
            $this->issueRepository,
            $this->formHelper
        );
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
                            ['id', 'number', 'title', 'project.name', 'priority', 'type.name', 'status.name', 'assignedTo'],
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
