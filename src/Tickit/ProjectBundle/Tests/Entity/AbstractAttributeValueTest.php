<?php

namespace Tickit\ProjectBundle\Tests\Entity;

use Tickit\ProjectBundle\Entity\AbstractAttribute;
use Tickit\ProjectBundle\Entity\AbstractAttributeValue;

/**
 * AbstractAttributeValueTest tests
 *
 * @package Tickit\ProjectBundle\Tests\Entity
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class AbstractAttributeValueTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Tests the factory() method
     *
     * @expectedException \InvalidArgumentException
     */
    public function testFactoryThrowsExceptionForInvalidAttributeType()
    {
        AbstractAttributeValue::factory('invalid type');
    }

    /**
     * Tests the factory() method
     *
     * @param string $type             The attribute type to test
     * @param string $expectedInstance The expected instance type
     *
     * @dataProvider getTypes
     */
    public function testFactoryProducesExpectedInstance($type, $expectedInstance)
    {
        $instance = AbstractAttributeValue::factory($type);

        $this->assertInstanceOf($expectedInstance, $instance);
    }

    /**
     * Gets attribute types for test
     *
     * @return array
     */
    public function getTypes()
    {
        return array(
            array(AbstractAttribute::TYPE_CHOICE, 'Tickit\ProjectBundle\Entity\ChoiceAttributeValue'),
            array(AbstractAttribute::TYPE_ENTITY, 'Tickit\ProjectBundle\Entity\EntityAttributeValue'),
            array(AbstractAttribute::TYPE_LITERAL, 'Tickit\ProjectBundle\Entity\LiteralAttributeValue')
        );
    }
}
