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

namespace Tickit\Bundle\ProjectBundle\Tests\Form\Type;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\Validator\Validation;
use Tickit\Bundle\CoreBundle\Tests\Form\Type\AbstractFormTypeTestCase;
use Tickit\Component\Model\Project\ChoiceAttribute;
use Tickit\Component\Model\Project\ChoiceAttributeChoice;
use Tickit\Component\Model\Project\ChoiceAttributeValue;
use Tickit\Component\Model\Project\EntityAttribute;
use Tickit\Component\Model\Project\EntityAttributeValue;
use Tickit\Component\Model\Project\LiteralAttribute;
use Tickit\Component\Model\Project\LiteralAttributeValue;
use Tickit\Component\Model\Project\Project;
use Tickit\Bundle\ProjectBundle\Form\Type\AttributeValueFormType;

/**
 * AttributeValueFormType tests
 *
 * @package Tickit\Bundle\ProjectBundle\Tests\Form\Type
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class AttributeValueFormTypeTest extends AbstractFormTypeTestCase
{
    /**
     * Setup
     */
    protected function setUp()
    {
        parent::setUp();

        $this->formType = new AttributeValueFormType();
    }

    /**
     * Ensures that a "not blank" attribute is built correctly
     *
     * @return void
     */
    public function testFormBuildsNotBlankAttributeCorrectly()
    {
        $attributeValue = $this->getLiteralAttributeValue(LiteralAttribute::VALIDATION_IP, false);

        $form = $this->factory->create($this->formType);

        $form->setData($attributeValue);

        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($attributeValue, $form->getData());

        $valueField = $form->get('value');
        $constraints = $valueField->getConfig()->getOption('constraints');
        $this->assertNotEmpty($constraints);

        $first = array_shift($constraints);
        $this->assertInstanceOf('Symfony\Component\Validator\Constraints\NotBlank', $first);
    }

    /**
     * Ensures that literal attributes are built correctly
     *
     * @param string $validationType          The validation type
     * @param string $expectedConstraintClass The expected constraint class for the validation type
     *
     * @dataProvider getLiteralAttributeValueData
     *
     * @return void
     */
    public function testFormBuildsLiteralAttributesCorrectly($validationType, $expectedConstraintClass)
    {
        $attributeValue = $this->getLiteralAttributeValue($validationType);

        $form = $this->factory->create($this->formType);
        $form->setData($attributeValue);

        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($attributeValue, $form->getData());

        $valueField = $form->get('value');
        $constraints = $valueField->getConfig()->getOption('constraints');
        $this->assertNotEmpty($constraints);

        $first = array_shift($constraints);
        $this->assertInstanceOf($expectedConstraintClass, $first);
    }

    /**
     * Ensures that entity attributes are built correctly
     *
     * @return void
     */
    public function testFormBuildsEntityAttributesCorrectly()
    {
        $attribute = new EntityAttribute();
        $attribute->setEntity('Tickit\Component\Model\Project\Project');

        $attributeValue = new EntityAttributeValue();
        $attributeValue->setAttribute($attribute)
                       ->setValue(1)
                       ->setProject(new Project());

        $form = $this->factory->create($this->formType);
        $form->setData($attributeValue);

        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($form->getData(), $attributeValue);

        $valueField = $form->get('value');
        $this->assertInstanceOf(
            'Symfony\Bridge\Doctrine\Form\Type\EntityType',
            $valueField->getConfig()->getType()->getInnerType()
        );
    }

    /**
     * Ensures that single select, collapsed choice attributes are built correctly
     *
     * @dataProvider getChoiceAttributeValueData
     *
     * @param boolean $multiple True if the choice attribute should allow multiple selections
     * @param boolean $expanded True if the choice attribute should render as an expanded control
     *
     * @return void
     */
    public function testFormBuildsChoiceAttributesCorrectly($multiple, $expanded)
    {
        $attributeValue = $this->getChoiceAttributeValue($multiple, $expanded);

        $form = $this->factory->create($this->formType);
        $form->setData($attributeValue);

        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($form->getData(), $attributeValue);

        $valueField = $form->get('value');
        $this->assertInstanceOf(
            'Symfony\Bridge\Doctrine\Form\Type\EntityType',
            $valueField->getConfig()->getType()->getInnerType()
        );

        $options = $valueField->getConfig()->getOptions();

        $this->assertFalse($options['expanded']);
        $this->assertFalse($options['multiple']);
    }

    /**
     * Gets test data for testFormBuildsChoiceAttributesCorrectly()
     *
     * @return array
     */
    public function getChoiceAttributeValueData()
    {
        return array(
            array(false, false)
        );
    }

    /**
     * Gets test data for testFormBuildsLiteralAttributesCorrectly()
     *
     * @return array
     */
    public function getLiteralAttributeValueData()
    {
        return array(
            array(
                LiteralAttribute::VALIDATION_DATE,
                'Symfony\Component\Validator\Constraints\Date'
            ),
            array(
                LiteralAttribute::VALIDATION_DATETIME,
                'Symfony\Component\Validator\Constraints\DateTime'
            ),
            array(
                LiteralAttribute::VALIDATION_FILE,
                'Symfony\Component\Validator\Constraints\File'
            ),
            array(
                LiteralAttribute::VALIDATION_EMAIL,
                'Symfony\Component\Validator\Constraints\Email'
            ),
            array(
                LiteralAttribute::VALIDATION_NUMBER,
                'Symfony\Component\Validator\Constraints\Type'
            ),
            array(
                LiteralAttribute::VALIDATION_DATETIME,
                'Symfony\Component\Validator\Constraints\DateTime'
            ),
            array(
                LiteralAttribute::VALIDATION_IP,
                'Symfony\Component\Validator\Constraints\Ip'
            ),
            array(
                LiteralAttribute::VALIDATION_URL,
                'Symfony\Component\Validator\Constraints\Url'
            ),
            array(
                LiteralAttribute::VALIDATION_STRING,
                'Symfony\Component\Validator\Constraints\Type'
            )
        );
    }

    /**
     * {@inheritDoc}
     */
    protected function configureExtensions()
    {
        $this->enableDoctrineExtension();
        $this->enableCoreExtension();
        $this->enableValidatorExtension();
    }

    /**
     * Gets a LiteralAttributeValue with a specified validation type
     *
     * @param string  $validationType The validation type
     * @param boolean $allowBlank     Whether the attribute can be left blank
     *
     * @return LiteralAttributeValue
     */
    private function getLiteralAttributeValue($validationType = LiteralAttribute::VALIDATION_STRING, $allowBlank = true)
    {
        $attribute = new LiteralAttribute();
        $attribute->setValidationType($validationType)
                  ->setAllowBlank($allowBlank)
                  ->setDefaultValue(2);
        $attributeValue = new LiteralAttributeValue();
        $attributeValue->setAttribute($attribute)
                       ->setProject(new Project());

        return $attributeValue;
    }

    /**
     * Gets a ChoiceAttributeValue with specified options
     *
     * @param boolean $allowMultiple True to allow multiple choices
     * @param boolean $expanded      True to show an expanded form element
     *
     * @return ChoiceAttributeValue
     */
    private function getChoiceAttributeValue($allowMultiple = false, $expanded = false)
    {
        $choice1 = new ChoiceAttributeChoice();
        $choice1->setName('Yes');

        $choice2 = new ChoiceAttributeChoice();
        $choice2->setName('No');

        $choice3 = new ChoiceAttributeChoice();
        $choice3->setName('Maybe');

        $choices = new ArrayCollection(array($choice1, $choice2, $choice3));

        $attribute = new ChoiceAttribute();
        $attribute->setAllowMultiple($allowMultiple)
                  ->setChoices($choices)
                  ->setExpanded($expanded);

        $value = new ArrayCollection(array($choice2));
        $attributeValue = new ChoiceAttributeValue();
        $attributeValue->setAttribute($attribute)
                       ->setValue($value)
                       ->setProject(new Project());

        return $attributeValue;
    }
}
