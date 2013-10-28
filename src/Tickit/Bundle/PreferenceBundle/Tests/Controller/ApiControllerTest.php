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

namespace Tickit\Bundle\PreferenceBundle\Tests\Controller;

use Symfony\Component\HttpFoundation\Request;
use Tickit\Bundle\CoreBundle\Tests\AbstractUnitTest;
use Tickit\Bundle\PreferenceBundle\Controller\ApiController;
use Tickit\Bundle\PreferenceBundle\Entity\Preference;

/**
 * ApiController tests
 *
 * @package Tickit\Bundle\PreferenceBundle\Tests\Controller
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class ApiControllerTest extends AbstractUnitTest
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $filterCollectionBuilder;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $preferenceRepo;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $baseHelper;

    /**
     * Setup
     */
    protected function setUp()
    {
        $this->filterCollectionBuilder = $this->getMockFilterCollectionBuilder();

        $this->preferenceRepo = $this->getMockBuilder('\Tickit\Bundle\PreferenceBundle\Entity\Repository\PreferenceRepository')
                                     ->disableOriginalConstructor()
                                     ->getMock();

        $this->baseHelper = $this->getMockBaseHelper();
    }
    
    /**
     * Tests the listAction() method
     */
    public function testListActionBuildsCorrectResponse()
    {
        $preference1 = new Preference();
        $preference1->setName('preference 1');

        $preference2 = new Preference();
        $preference2->setName('preference 2');

        $preferences = [$preference1, $preference2];

        $filters = $this->getMockBuilder('\Tickit\Bundle\CoreBundle\Filters\Collection\FilterCollection')
                        ->disableOriginalConstructor()
                        ->getMock();

        $request = new Request();
        $this->baseHelper->expects($this->once())
                         ->method('getRequest')
                         ->will($this->returnValue($request));

        $this->filterCollectionBuilder->expects($this->once())
                                      ->method('buildFromRequest')
                                      ->with($request)
                                      ->will($this->returnValue($filters));

        $this->preferenceRepo->expects($this->once())
                             ->method('findByFilters')
                             ->with($filters)
                             ->will($this->returnValue($preferences));

        $expectedData = [['preference data'], ['preference data']];
        $decorator = $this->getMockObjectDecorator();
        $this->trainBaseHelperToReturnObjectCollectionDecorator($decorator);
        $this->trainObjectCollectionDecoratorToExpectPreferenceCollection($decorator, $preferences, $expectedData);

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
        return new ApiController($this->filterCollectionBuilder, $this->preferenceRepo, $this->baseHelper);
    }

    private function trainBaseHelperToReturnObjectCollectionDecorator(\PHPUnit_Framework_MockObject_MockObject $decorator)
    {
        $this->baseHelper->expects($this->once())
                         ->method('getObjectCollectionDecorator')
                         ->will($this->returnValue($decorator));
    }

    private function trainObjectCollectionDecoratorToExpectPreferenceCollection(
        \PHPUnit_Framework_MockObject_MockObject $objectDecorator,
        array $preferences,
        array $returnData
    ) {
        $objectDecorator->expects($this->once())
                        ->method('decorate')
                        ->with($preferences, ['id', 'name', 'systemName', 'type'])
                        ->will($this->returnValue($returnData));
    }
}
