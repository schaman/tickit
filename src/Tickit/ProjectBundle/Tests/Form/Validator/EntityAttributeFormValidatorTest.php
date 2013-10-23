<?php

namespace Tickit\ProjectBundle\Tests\Form\Validator;

use Tickit\ProjectBundle\Entity\EntityAttribute;
use Tickit\ProjectBundle\Form\Validator\EntityAttributeFormValidator;

/**
 * EntityAttributeFormValidator tests
 *
 * @package Tickit\ProjectBundle\Tests\Form\Validator
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class EntityAttributeFormValidatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Validator under test
     *
     * @var EntityAttributeFormValidator
     */
    private $validator;

    /**
     * Setup
     */
    protected function setUp()
    {
        $this->validator = new EntityAttributeFormValidator();
    }

    /**
     * Tests the isEntityAttributeValid() method
     */
    public function testIsEntityAttributeValidAddsViolationForInvalidEntity()
    {
        $attribute = new EntityAttribute();
        $attribute->setEntity('Non\Existent\Entity\Class');

        $context = $this->getMockExecutionContext();

        $context->expects($this->once())
                ->method('addViolationAt')
                ->with('entity', 'Oops, looks like that entity doesn\'t exist!');

        $this->validator->isEntityAttributeValid($attribute, $context);
    }

    /**
     * Tests the isEntityAttributeValid() method
     */
    public function testIsEntityAttributeValidDoesNotAddViolationForValidEntity()
    {
        $attribute = new EntityAttribute();
        $attribute->setEntity('Tickit\ProjectBundle\Entity\EntityAttribute');

        $context = $this->getMockExecutionContext();
        $context->expects($this->exactly(0))
                ->method('addViolationAt');

        $this->validator->isEntityAttributeValid($attribute, $context);

    }

    /**
     * Gets a mock of ExecutionContextInterface
     *
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function getMockExecutionContext()
    {
        return $context = $this->getMockBuilder('\Symfony\Component\Validator\ExecutionContextInterface')
                               ->disableOriginalConstructor()
                               ->getMock();
    }
}
