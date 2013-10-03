<?php

namespace Tickit\ProjectBundle\Tests\Entity;

use Tickit\ProjectBundle\Entity\LiteralAttribute;
use Tickit\ProjectBundle\Entity\LiteralAttributeValue;

/**
 * LiteralAttributeValue tests
 *
 * @package Tickit\ProjectBundle\Tests\Entity
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class LiteralAttributeValueTest extends \PHPUnit_Framework_TestCase
{
    /**
     * The attribute under test
     *
     * @var LiteralAttributeValue
     */
    private $attributeValue;

    /**
     * Setup
     */
    protected function setUp()
    {
        $this->attributeValue = new LiteralAttributeValue();
    }
    
    /**
     * Tests setValue() method
     */
    public function testSetValueFormatsDateCorrectly()
    {
        $now = $this->getMock('\DateTime');
        $now->expects($this->once())
            ->method('format')
            ->with('Y-m-d');

        $attribute = new LiteralAttribute();
        $attribute->setValidationType(LiteralAttribute::VALIDATION_DATE);
        $this->attributeValue
             ->setAttribute($attribute)
             ->setValue($now);
    }

    /**
     * Tests setValue() method
     */
    public function testSetValueFormatsDateTimeCorrectly()
    {
        $now = $this->getMock('\DateTime');
        $now->expects($this->once())
            ->method('format')
            ->with('Y-m-d H:i:s');

        $attribute = new LiteralAttribute();
        $attribute->setValidationType(LiteralAttribute::VALIDATION_DATETIME);

        $this->attributeValue
             ->setAttribute($attribute)
             ->setValue($now);
    }

    /**
     * Tests getValue() method
     */
    public function testGetValueReturnsSameInstanceForDateTime()
    {
        $now = new \DateTime();

        $attribute = new LiteralAttribute();
        $attribute->setValidationType(LiteralAttribute::VALIDATION_DATETIME);

        $this->attributeValue
             ->setAttribute($attribute)
             ->setValue($now);

        $this->assertEquals($now, $this->attributeValue->getValue());
    }
}
