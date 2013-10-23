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

namespace Tickit\CoreBundle\Tests\Decorator;

use Tickit\CoreBundle\Decorator\DomainObjectArrayDecorator;
use Tickit\CoreBundle\Tests\Decorator\Mock\MockDomainObject;

/**
 * DomainObjectArrayDecorator tests
 *
 * @package Tickit\CoreBundle\Tests\Decorator
 * @author  James Halsall <james.t.halsall@googlemail.com>
 * @author  Mark Wilson <mark@89allport.co.uk>
 */
class DomainObjectArrayDecoratorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Tests the decorate() method
     *
     * @expectedException \InvalidArgumentException
     *
     * @return void
     */
    public function testDecorateThrowsExceptionForNonObject()
    {
        $decorator = new DomainObjectArrayDecorator();
        $decorator->decorate('', array());
    }

    /**
     * Tests the decorate() method
     *
     * @expectedException \RuntimeException
     *
     * @return void
     */
    public function testDecorateThrowsExceptionForInaccessibleProperty()
    {
        $decorator = new DomainObjectArrayDecorator();
        $decorator->decorate(new MockDomainObject(), array('fake'));
    }

    /**
     * Tests the decorate() method
     *
     * @expectedException \RuntimeException
     *
     * @return void
     */
    public function testDecorateThrowsExceptionForInaccessibleChildProperty()
    {
        $decorator = new DomainObjectArrayDecorator();
        $decorator->decorate(new MockDomainObject(), array('childProperty.fake'));
    }

    /**
     * Tests the decorate() method
     *
     * @return void
     */
    public function testDecorateHandlesMockObjectCorrectly()
    {
        $decorator = new DomainObjectArrayDecorator();
        $mock      = new MockDomainObject();

        $decorated = $decorator->decorate(
            $mock,
            array(
                'name',
                'active',
                'enabled',
                'date',
                'childObject.enabled',
                'childObject.childObject.enabled'
            ),
            array(
                'static' => 'value'
            )
        );

        $this->assertInternalType('array', $decorated);
        $this->assertEquals('name', $decorated['name']);
        $this->assertTrue($decorated['active']);
        $this->assertFalse($decorated['enabled']);
        $this->assertEquals(date('Y-m-d H:i:s'), $decorated['date']);
        $this->assertTrue($decorated['childObject.enabled']);
        $this->assertTrue($decorated['childObject.childObject.enabled']);
        $this->assertEquals('value', $decorated['static']);
    }
}
