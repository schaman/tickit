<?php

namespace Tickit\ProjectBundle\Tests\Entity;

use Tickit\ProjectBundle\Entity\AbstractAttribute;

/**
 * AbstractAttribute tests
 *
 * @package Tickit\ProjectBundle\Tests\Entity
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class AbstractAttributeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Tests the factory() method
     */
    public function testFactoryReturnsCorrectInstanceForChoiceType()
    {
        $attribute = AbstractAttribute::factory(AbstractAttribute::TYPE_CHOICE);

        $this->assertInstanceOf('\Tickit\ProjectBundle\Entity\ChoiceAttribute', $attribute);
    }

    /**
     * Tests the factory() method
     */
    public function testFactoryReturnsCorrectInstanceForEntityType()
    {
        $attribute = AbstractAttribute::factory(AbstractAttribute::TYPE_ENTITY);

        $this->assertInstanceOf('\Tickit\ProjectBundle\Entity\EntityAttribute', $attribute);
    }

    /**
     * Tests the factory() method
     */
    public function testFactoryReturnsCorrectInstanceForLiteralType()
    {
        $attribute = AbstractAttribute::factory(AbstractAttribute::TYPE_LITERAL);

        $this->assertInstanceOf('\Tickit\ProjectBundle\Entity\LiteralAttribute', $attribute);
    }

    /**
     * Tests the factory() method
     *
     * @expectedException \InvalidArgumentException
     */
    public function testFactoryThrowsExceptionForInvalidType()
    {
        AbstractAttribute::factory('invalid type');
    }
}
