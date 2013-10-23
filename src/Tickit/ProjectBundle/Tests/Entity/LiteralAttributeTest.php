<?php

namespace Tickit\ProjectBundle\Tests\Entity;

use Tickit\ProjectBundle\Entity\AbstractAttribute;
use Tickit\ProjectBundle\Entity\LiteralAttribute;

/**
 * LiteralAttribute tests
 *
 * @package Tickit\ProjectBundle\Tests\Entity
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class LiteralAttributeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * The attribute under test
     *
     * @var LiteralAttribute
     */
    private $attribute;

    /**
     * Setup
     */
    protected function setUp()
    {
        $this->attribute = new LiteralAttribute();
    }
    
    /**
     * Tests the setValidationType() method
     *
     * @expectedException \InvalidArgumentException
     */
    public function testSetValidationTypeThrowsExceptionForInvalidType()
    {
        $this->attribute->setValidationType('invalid type');
    }

    /**
     * Tests the setValidationType() method
     */
    public function testSetValidationTypeAcceptsValidType()
    {
        $this->attribute->setValidationType(LiteralAttribute::VALIDATION_DATE);

        $this->assertEquals(LiteralAttribute::VALIDATION_DATE, $this->attribute->getValidationType());
    }

    /**
     * Tests the getType() method
     */
    public function testGetTypeReturnsCorrectType()
    {
        $this->assertEquals(AbstractAttribute::TYPE_LITERAL, $this->attribute->getType());
    }
}
