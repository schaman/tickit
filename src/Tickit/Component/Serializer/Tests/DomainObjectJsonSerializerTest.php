<?php

namespace Tickit\Component\Serializer\Tests;

use Tickit\Component\Serializer\DomainObjectJsonSerializer;

/**
 * DomainObjectJsonSerializer tests
 *
 * @package Tickit\Component\Serializer\Tests
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class DomainObjectJsonSerializerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $serializer;

    /**
     * Setup
     */
    protected function setUp()
    {
        $this->serializer = $this->getMock('\JMS\Serializer\SerializerInterface');
    }

    /**
     * @dataProvider getSerializeFixtures
     */
    public function testSerialize($object)
    {
        $this->serializer->expects($this->once())
                         ->method('serialize')
                         ->with($object, 'json')
                         ->will($this->returnValue('serialized value'));

        $serialized = $this->getSerializer()->serialize($object);

        $this->assertEquals('serialized value', $serialized);
    }

    /**
     * @return array
     */
    public function getSerializeFixtures()
    {
        $object1 = new \stdClass();
        $object1->property = 'hello';
        $object2 = new \stdClass();
        $object2->anotherProperty = 'goodbye';

        return [
            [$object1],
            [$object2]
        ];
    }

    /**
     * @return DomainObjectJsonSerializer
     */
    private function getSerializer()
    {
        return new DomainObjectJsonSerializer($this->serializer);
    }
}
