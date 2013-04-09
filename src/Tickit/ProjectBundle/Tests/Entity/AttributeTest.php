<?php

namespace Tickit\ProjectBundle\Tests\Entity;

use PHPUnit_Framework_TestCase;
use Tickit\ProjectBundle\Entity\Attribute;

/**
 * Tests for the Attribute entity
 *
 * @package Tickit\ProjectBundle\Tests\Entity
 * @author  James Halsall <jhalsall@rippleffect.com>
 */
class AttributeTest extends PHPUnit_Framework_TestCase
{
    /**
     * Tests the setType() method
     *
     * @expectedException \InvalidArgumentException
     *
     * @return void
     */
    public function testSetTypeThrowsExceptionForInvalidTypes()
    {
        $attribute = new Attribute();
        $attribute->setType('something random');
    }

    /**
     * Tests the setType() method
     *
     * @return void
     */
    public function testSetTypeAcceptsValidTypes()
    {
        $attribute = new Attribute();
        $attribute->setType(Attribute::TYPE_CHOICE);

        $this->assertEquals(Attribute::TYPE_CHOICE, $attribute->getType());
    }


    /**
     * Tests the setMetaData() method
     *
     * @expectedException \InvalidArgumentException
     *
     * @return void
     */
    public function testSetMetaDataThrowsExceptionForInvalidArgument()
    {
        $attribute = new Attribute();
        $attribute->setMetaDeta('blah');
    }

    /**
     * Tests the setMetaData() method
     *
     * @return void
     */
    public function testSetMetaDataAcceptsJsonEncodedString()
    {
        $attribute = new Attribute();
        $attribute->setMetaDeta(json_encode(array('1' => 2)));

        $this->assertEquals(json_encode(array('1' => 2)), $attribute->getMetaDeta());
    }

    /**
     * Tests the getMetaData() method
     *
     * @return void
     */
    public function testGetMetaDataReturnsDecodedDataCorrectly()
    {
        $object = new \stdClass();
        $object->property = 1;
        $object->something = 'some string';

        $attribute = new Attribute();
        $attribute->setMetaDeta(json_encode($object));

        $this->assertEquals($object, $attribute->getMetaDeta(true));
    }
}
