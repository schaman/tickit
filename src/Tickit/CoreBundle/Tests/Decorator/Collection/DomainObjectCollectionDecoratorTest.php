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

namespace Tickit\CoreBundle\Tests\Decorator\Collection;

use Tickit\CoreBundle\Decorator\Collection\DomainObjectCollectionDecorator;
use Tickit\CoreBundle\Tests\Decorator\Mock\MockDomainObject;

/**
 * DomainObjectCollectionDecorator tests
 *
 * @package Tickit\CoreBundle\Tests\Decorator\Collection
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class DomainObjectCollectionDecoratorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $decorator;

    /**
     * Setup
     */
    protected function setUp()
    {
        $this->decorator = $this->getMockForAbstractClass(
            '\Tickit\CoreBundle\Decorator\DomainObjectDecoratorInterface'
        );
    }

    /**
     * Tests the decorate() method
     */
    public function testDecorateCorrectlyDecoratesObjects()
    {
        $data = [new MockDomainObject(), new MockDomainObject()];
        $propertyNames = ['name', 'active', 'enabled', 'date', 'childObject.enabled'];
        $staticProperties = ['csrf-token' => 'some value'];

        $this->decorator->expects($this->exactly(2))
                        ->method('decorate')
                        ->will($this->returnValue(array('decorated')));

        $this->decorator->expects($this->at(0))
                        ->method('decorate')
                        ->with($data[0], $propertyNames, $staticProperties);

        $return = $this->getCollectionDecorator()->decorate($data, $propertyNames, $staticProperties);
        $this->assertEquals([['decorated'], ['decorated']], $return);
    }

    /**
     * Gets a new decorator instance
     *
     * @return DomainObjectCollectionDecorator
     */
    private function getCollectionDecorator()
    {
        return new DomainObjectCollectionDecorator($this->decorator);
    }
}
