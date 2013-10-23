<?php

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
