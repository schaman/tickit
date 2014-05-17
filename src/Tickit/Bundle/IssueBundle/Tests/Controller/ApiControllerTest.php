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
use Tickit\Component\Model\Issue\IssueNumber;
use Tickit\Component\Pagination\PageData;
use Tickit\Component\Pagination\Resolver\PageResolver;
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
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $dataTransformer;

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
        $this->dataTransformer = $this->getMockBuilder('\Tickit\Component\Issue\DataTransformer\StringToIssueNumberDataTransformer')
                                      ->disableOriginalConstructor()
                                      ->getMock();
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
                              ->with($filters, 2)
                              ->will($this->returnValue($this->paginator));

        $serializer = $this->getMockSerializer();

        $serializedData = [['issue'], ['issue']];
        $this->trainBaseHelperToReturnSerializer($serializer);
        $this->trainSerializerToReturnSerializedValue(
            $serializer,
            PageData::create($this->paginator, 2, PageResolver::ITEMS_PER_PAGE, 2),
            $serializedData
        );

        $response = $this->getController()->listAction(2);
        $this->assertEquals($serializedData, json_decode($response->getContent(), true));
    }

    /**
     * @dataProvider getFindByIssueNumberActionFixtures
     */
    public function testFindByIssueNumberAction($issueNumberString, IssueNumber $issueNumberObject, Issue $issue = null)
    {
        $this->dataTransformer->expects($this->once())
                              ->method('transform')
                              ->with($issueNumberString)
                              ->will($this->returnValue($issueNumberObject));

        $this->issueRepository->expects($this->once())
                              ->method('findIssueByIssueNumber')
                              ->with($issueNumberObject)
                              ->will($this->returnValue($issue));



        if (null === $issue) {
            $response = $this->getController()->findByIssueNumberAction($issueNumberString);

            $this->assertEquals('No issue found', json_decode($response->getContent()));
        } else {
            $serializer = $this->getMockSerializer();
            $this->trainBaseHelperToReturnSerializer($serializer);
            $this->trainSerializerToReturnSerializedValue($serializer, $issue, 'serialized issue');

            $response = $this->getController()->findByIssueNumberAction($issueNumberString);
            $this->assertEquals('serialized issue', json_decode($response->getContent()));
        }
    }

    /**
     * @return array
     */
    public function getFindByIssueNumberActionFixtures()
    {
        $issueNumber1 = new IssueNumber('PROJ', 10001);
        $issueNumber2 = new IssueNumber('PROJ', 10002);

        return [
            ['PROJ10001', $issueNumber1, null],
            ['PROJ10002', $issueNumber2, new Issue()]
        ];
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
            $this->formHelper,
            $this->dataTransformer
        );
    }

    private function trainBaseHelperToReturnRequest(Request $request)
    {
        $this->baseHelper->expects($this->once())
                         ->method('getRequest')
                         ->will($this->returnValue($request));
    }

    private function trainPaginatorToReturnIterator(array $data)
    {
        $this->paginator->expects($this->any())
                        ->method('getIterator')
                        ->will($this->returnValue(new \ArrayIterator($data)));
    }

    private function trainPaginatorToReturnCount($count)
    {
        $this->paginator->expects($this->once())
                        ->method('count')
                        ->will($this->returnValue($count));
    }

    private function trainBaseHelperToReturnSerializer($serializer)
    {
        $this->baseHelper->expects($this->once())
                         ->method('getSerializer')
                         ->will($this->returnValue($serializer));
    }

    private function trainSerializerToReturnSerializedValue(
        \PHPUnit_Framework_MockObject_MockObject $serializer,
        $valueToSerialize,
        $serializedValue
    ) {
        $serializer->expects($this->once())
                   ->method('serialize')
                   ->with($valueToSerialize)
                   ->will($this->returnValue(json_encode($serializedValue)));
    }
}
