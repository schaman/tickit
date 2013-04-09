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
}
