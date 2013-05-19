<?php

namespace Tickit\PermissionBundle\Tests\Model\Decorator;

use Tickit\PermissionBundle\Model\Decorator\PermissionsJsonDecorator;
use Tickit\PermissionBundle\Model\Permission;

/**
 * Tests for PermissionsJsonDecorator
 *
 * @package Tickit\PermissionBundle\Tests\Model\Decorator
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class PermissionsJsonDecoratorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Tests the parseData() method
     *
     * @expectedException \InvalidArgumentException
     *
     * @return void
     */
    public function testParseDataThrowsExceptionForInvalidData()
    {
        $decorator = new PermissionsJsonDecorator('something stupid');
        $decorator->render();
    }

    /**
     * Tests the parseData() method
     *
     * @expectedException \InvalidArgumentException
     *
     * @return void
     */
    public function testParseDataThrowsExceptionForArrayOfInvalidTypes()
    {
        $decorator = new PermissionsJsonDecorator(array(new \stdClass()));
        $decorator->render();
    }

    /**
     * Tests the parseData() method
     *
     * @param array $data The data to run the test
     *
     * @dataProvider getRawData
     *
     * @return void
     */
    public function testParseDataReturnsCorrectStructureForValidData(array $data)
    {
        $decorator = new PermissionsJsonDecorator($data);
        $output = $decorator->render();

        $decoded = json_decode($output, true);
        $this->assertInternalType('array', $decoded);
        $this->assertEquals(2, count($decoded['permissions']));

        $expected1 = array(
            'name' => 'permission 1',
            'overridden' => true,
            'values' => array(
                'group' => true,
                'user' => false
            )
        );

        $this->assertEquals($expected1, $decoded['permissions'][1]);

        $expected2 = array(
            'name' => 'permission 2',
            'overridden' => false,
            'values' => array(
                'group' => false,
                'user' => null
            )
        );

        $this->assertEquals($expected2, $decoded['permissions'][2]);
    }

    /**
     * Data provider
     *
     * @return array
     */
    public function getRawData()
    {
        $permission1 = new Permission();
        $permission1->setId(1);
        $permission1->setGroupValue(true);
        $permission1->setUserValue(false);
        $permission1->setOverridden(true);
        $permission1->setName('permission 1');

        $permission2 = new Permission();
        $permission2->setId(2);
        $permission2->setGroupValue(false);
        $permission2->setOverridden(false);
        $permission2->setName('permission 2');

        return array(array(array($permission1, $permission2)));
    }
}
