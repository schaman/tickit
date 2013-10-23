<?php

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
