<?php

/*
 * Tickit, an open source web based bug management tool.
 * 
 * Copyright (C) 2013  Tickit Project <http://tickit.io>
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
use Tickit\Component\Filter\Collection\FilterCollection;
use Tickit\Bundle\CoreBundle\Tests\AbstractUnitTest;
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
    private $baseHelper;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $csrfHelper;

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

        $this->baseHelper = $this->getMockBaseHelper();
        $this->csrfHelper = $this->getMockCsrfHelper();
    }
    
    /**
     * Tests the listAction() method
     */
    public function testListActionBuildsCorrectResponse()
    {
        $request = new Request();
        $filters = new FilterCollection();

        $this->trainBaseHelperToReturnRequest($request);
        $this->trainFilterBuilderToReturnFilters($filters, $request);

        $project1 = new Project();
        $project1->setName('Project 1');
        $project2 = new Project();
        $project2->setName('Project 2');
        $projects = array($project1, $project2);

        $this->projectRepo->expects($this->once())
                          ->method('findByFilters')
                          ->with($filters)
                          ->will($this->returnValue($projects));

        $this->csrfHelper->expects($this->once())
                         ->method('generateCsrfToken')
                         ->with(ProjectController::CSRF_DELETE_INTENTION)
                         ->will($this->returnValue('csrf-token-value'));

        $expectedData = [['project'], ['project']];

        $decorator = $this->getMockObjectDecorator();
        $this->trainBaseHelperToReturnObjectCollectionDecorator($decorator);
        $this->trainObjectCollectionDecoratorToExpectProjectCollection($decorator, $projects, $expectedData);

        $response = $this->getController()->listAction();
        $this->assertEquals($expectedData, json_decode($response->getContent(), true));
    }

    /**
     * Tests the attributesListAction() method
     */
    public function testAttributesListActionBuildsCorrectResponse()
    {
        $request = new Request();
        $filters = new FilterCollection();

        $this->trainBaseHelperToReturnRequest($request);
        $this->trainFilterBuilderToReturnFilters($filters, $request);

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
            $this->csrfHelper
        );
    }

    private function trainBaseHelperToReturnRequest(Request $request)
    {
        $this->baseHelper->expects($this->once())
                         ->method('getRequest')
                         ->will($this->returnValue($request));
    }

    private function trainFilterBuilderToReturnFilters(FilterCollection $filters, Request $request)
    {
        $this->filterBuilder->expects($this->once())
                            ->method('buildFromRequest')
                            ->with($request)
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
        array $projects,
        array $returnData
    ) {
        $objectDecorator->expects($this->once())
                        ->method('decorate')
                        ->with(
                            $projects,
                            ['id', 'name', 'created'],
                            ['csrf_token' => 'csrf-token-value']
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
}
