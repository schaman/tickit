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

namespace Tickit\Bundle\ProjectBundle\Tests\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Csrf\CsrfToken;
use Tickit\Bundle\ProjectBundle\Form\Type\FilterFormType;
use Tickit\Component\Filter\Collection\FilterCollection;
use Tickit\Component\Test\AbstractUnitTest;
use Tickit\Bundle\ProjectBundle\Controller\ApiController;
use Tickit\Bundle\ProjectBundle\Controller\ProjectController;
use Tickit\Component\Model\Project\ChoiceAttribute;
use Tickit\Component\Model\Project\LiteralAttribute;
use Tickit\Component\Model\Project\Project;

/**
 * ApiControllerTest tests
 *
 * @package Tickit\Bundle\ProjectBundle\Tests\Controller
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
    private $projectRepo;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $attributeRepo;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $paginator;

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

        $this->projectRepo = $this->getMockBuilder('Tickit\Bundle\ProjectBundle\Doctrine\Repository\ProjectRepository')
                                  ->disableOriginalConstructor()
                                  ->getMock();

        $this->attributeRepo = $this->getMockBuilder('Tickit\Bundle\ProjectBundle\Doctrine\Repository\AttributeRepository')
                                    ->disableOriginalConstructor()
                                    ->getMock();

        $this->paginator = $this->getMockPaginator();
        $this->baseHelper = $this->getMockBaseHelper();
        $this->csrfHelper = $this->getMockCsrfHelper();
        $this->formHelper = $this->getMockFormHelper();
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

        $this->trainFilterBuilderToReturnFilters($filters, $filterData);

        $project1 = new Project();
        $project1->setName('Project 1');
        $project2 = new Project();
        $project2->setName('Project 2');
        $projects = array($project1, $project2);

        $this->trainPaginatorToReturnIterator($projects);
        $this->trainPaginatorToReturnCount(2);

        $this->projectRepo->expects($this->once())
                          ->method('findByFilters')
                          ->with($filters, 1)
                          ->will($this->returnValue($this->paginator));

        $this->csrfHelper->expects($this->once())
                         ->method('generateCsrfToken')
                         ->with(ProjectController::CSRF_DELETE_INTENTION)
                         ->will($this->returnValue(new CsrfToken('id', 'csrf-token-value')));

        $expectedData = ['data' => [['project'], ['project']], 'total' => 2, 'pages' => 1, 'currentPage' => 1];

        $decorator = $this->getMockObjectDecorator();
        $this->trainBaseHelperToReturnObjectCollectionDecorator($decorator);
        $this->trainObjectCollectionDecoratorToExpectProjectCollection($decorator, new \ArrayIterator($projects), $expectedData['data']);

        $response = $this->getController()->listAction(1);
        $this->assertEquals($expectedData, json_decode($response->getContent(), true));
    }

    /**
     * Tests the attributesListAction() method
     */
    public function testAttributesListActionBuildsCorrectResponse()
    {
        $filters = new FilterCollection();

        $attribute1 = new LiteralAttribute();
        $attribute1->setName('attribute 1');
        $attribute2 = new ChoiceAttribute();
        $attribute2->setName('attribute 2');
        $attributes = [$attribute1, $attribute2];

        $this->attributeRepo->expects($this->once())
                            ->method('findByFilters')
                            ->with($filters)
                            ->will($this->returnValue($attributes));

        $expectedData = [['attribute'], ['attribute']];
        $decorator = $this->getMockObjectDecorator();
        $this->trainBaseHelperToReturnObjectCollectionDecorator($decorator);
        $this->trainObjectCollectionDecoratorToExpectAttributeCollection($decorator, $attributes, $expectedData);

        $response = $this->getController()->attributesListAction();
        $this->assertEquals($expectedData, json_decode($response->getContent(), true));
    }

    /**
     * Gets the controller instance
     *
     * @return ApiController
     */
    private function getController()
    {
        return new ApiController(
            $this->filterBuilder,
            $this->projectRepo,
            $this->attributeRepo,
            $this->baseHelper,
            $this->csrfHelper,
            $this->formHelper
        );
    }

    private function trainBaseHelperToReturnRequest(Request $request)
    {
        $this->baseHelper->expects($this->once())
                         ->method('getRequest')
                         ->will($this->returnValue($request));
    }

    private function trainFilterBuilderToReturnFilters(FilterCollection $filters, array $data)
    {
        $this->filterBuilder->expects($this->once())
                            ->method('buildFromArray')
                            ->with($data)
                            ->will($this->returnValue($filters));
    }

    private function trainBaseHelperToReturnObjectCollectionDecorator(\PHPUnit_Framework_MockObject_MockObject $decorator)
    {
        $this->baseHelper->expects($this->once())
                         ->method('getObjectCollectionDecorator')
                         ->will($this->returnValue($decorator));
    }

    private function trainObjectCollectionDecoratorToExpectProjectCollection(
        \PHPUnit_Framework_MockObject_MockObject $objectDecorator,
        $projects,
        array $returnData
    ) {
        $objectDecorator->expects($this->once())
                        ->method('decorate')
                        ->with(
                            $projects,
                            ['id', 'name', 'createdAt'],
                            ['csrfToken' => 'csrf-token-value']
                        )
                        ->will($this->returnValue($returnData));
    }

    private function trainObjectCollectionDecoratorToExpectAttributeCollection(
        \PHPUnit_Framework_MockObject_MockObject $objectDecorator,
        array $attributes,
        array $returnData
    ) {
        $objectDecorator->expects($this->once())
                        ->method('decorate')
                        ->with($attributes, ['id', 'type', 'name'])
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
