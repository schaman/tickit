<?php

namespace Tickit\Component\Serializer\Tests;

use Faker\Factory;
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
        return [
            [$this->getDummyObject()],
            [$this->getDummyObject()]
        ];
    }

    /**
     * @dataProvider getSerializeIterableFixtures
     */
    public function testSerializeIterable($iterable)
    {
        $this->serializer->expects($this->exactly(count($iterable)))
                         ->method('serialize')
                         ->will($this->returnValue('serialized value'));

        $i = 0;
        foreach ($iterable as $object) {
            $this->serializer->expects($this->at($i))
                             ->method('serialize')
                             ->with($object);

            $i++;
        }

        $return = $this->getSerializer()->serializeIterable($iterable);

        $this->assertEquals(gettype($iterable), gettype($return));
        foreach ($return as $serialized) {
            $this->assertEquals('serialized value', $serialized);
        }
    }

    /**
     * @return array
     */
    public function getSerializeIterableFixtures()
    {
        $objectArray = [
            $this->getDummyObject(),
            $this->getDummyObject()
        ];

        $iterator = new \ArrayIterator($objectArray);

        return [
            [$objectArray],
            [$iterator]
        ];
    }

    /**
     * @return \stdClass
     */
    private function getDummyObject()
    {
        $faker = Factory::create();

        $object = new \stdClass();
        $object->{$faker->word} = $faker->word;
        $object->{$faker->word} = $faker->word;

        return $object;
    }

    /**
     * @return DomainObjectJsonSerializer
     */
    private function getSerializer()
    {
        return new DomainObjectJsonSerializer($this->serializer);
    }
}
