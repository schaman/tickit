<?php

/*
 * Tickit, an source web based bug management tool.
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

namespace Tickit\NavigationBundle\Tests\Controller;

use Tickit\NavigationBundle\Controller\ApiController;
use Tickit\NavigationBundle\Model\NavigationItem;

/**
 * ApiController tests
 *
 * @package Tickit\NavigationBundle\Tests\Controller
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class ApiControllerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $navigationBuilder;

    /**
     * Setup
     */
    protected function setUp()
    {
        $this->navigationBuilder = $this->getMockBuilder('Tickit\NavigationBundle\Builder\NavigationBuilder')
                                        ->disableOriginalConstructor()
                                        ->getMock();
    }
    
    /**
     * Tests the navItemsAction() method
     */
    public function testNavItemsActionBuildsCorrectResponse()
    {
        $item1 = new NavigationItem('item 1', 'test', 1);
        $item2 = new NavigationItem('item 2', 'test2', 2);
        $items = new \SplPriorityQueue();
        $items->insert($item1, $item1->getPriority());
        $items->insert($item2, $item2->getPriority());

        $this->navigationBuilder->expects($this->once())
                                ->method('build')
                                ->will($this->returnValue($items));

        $response = $this->getController()->navItemsAction();
        $expectedData = array(
            array(
                'name' => $item2->getText(),
                'routeName' => $item2->getRouteName(),
                'active' => false
            ),
            array(
                'name' => $item1->getText(),
                'routeName' => $item1->getRouteName(),
                'active' => false
            )
        );

        $this->assertEquals($expectedData, json_decode($response->getContent(), true));
    }

    /**
     * Gets a new controller instance
     *
     * @return ApiController
     */
    private function getController()
    {
        return new ApiController($this->navigationBuilder);
    }
}
