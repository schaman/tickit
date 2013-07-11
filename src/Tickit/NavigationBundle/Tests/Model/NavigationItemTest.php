<?php

namespace Tickit\NavigationBundle\Tests\Model;

use Tickit\NavigationBundle\Model\NavigationItem;

/**
 * NavigationItem tests
 *
 * @package Tickit\NavigationBundle\Tests\Model
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class NavigationItemTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Tests the __construct() method
     *
     * @return void
     */
    public function testNavigationItemSetsCorrectValuesFromConstructor()
    {
        $params = array(
            'key' => 1,
            'key2' => 'value',
            'key3' => array(
                'subkey' => 'value'
            )
        );

        $item = new NavigationItem('navigation text', 'route_name', -99, $params);

        $this->assertEquals('navigation text', $item->getText());
        $this->assertEquals('route_name', $item->getRouteName());
        $this->assertEquals(-99, $item->getPriority());
        $this->assertEquals($params, $item->getParams());
    }
}
