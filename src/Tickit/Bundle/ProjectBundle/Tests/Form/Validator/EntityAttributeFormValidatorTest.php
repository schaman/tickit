<?php

/*
 * Tickit, an open source web based bug management tool.
 *
 * Copyright (C) 2014  Tickit Project <http://tickit.io>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Tickit\Bundle\ProjectBundle\Tests\Form\Validator;

use Tickit\Component\Model\Project\EntityAttribute;
use Tickit\Bundle\ProjectBundle\Form\Validator\EntityAttributeFormValidator;

/**
 * EntityAttributeFormValidator tests
 *
 * @package Tickit\Bundle\ProjectBundle\Tests\Form\Validator
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
        $attribute->setEntity('Tickit\Component\Model\Project\EntityAttribute');

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
