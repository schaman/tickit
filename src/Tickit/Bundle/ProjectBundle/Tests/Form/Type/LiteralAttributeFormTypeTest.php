<?php

/*
 * Tickit, an open source web based bug management tool.
 * 
 * Copyright (C) 2013  Tickit Project <http://tickit.io>
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

namespace Tickit\ProjectBundle\Tests\Form\Type;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Form\Extension\Core\CoreExtension;
use Symfony\Component\Form\PreloadedExtension;
use Tickit\Bundle\CoreBundle\Tests\Form\Type\AbstractFormTypeTestCase;
use Tickit\ProjectBundle\Entity\AbstractAttribute;
use Tickit\ProjectBundle\Entity\ChoiceAttribute;
use Tickit\ProjectBundle\Entity\ChoiceAttributeChoice;
use Tickit\ProjectBundle\Entity\LiteralAttribute;
use Tickit\ProjectBundle\Form\Type\ChoiceAttributeChoiceFormType;
use Tickit\ProjectBundle\Form\Type\LiteralAttributeFormType;

/**
 * LiteralAttributeFormType tests.
 *
 * @package Tickit\ProjectBundle\Tests\Form\Type
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class LiteralAttributeFormTypeTest extends AbstractFormTypeTestCase
{
    /**
     * Setup
     */
    protected function setUp()
    {
        $this->formType = new LiteralAttributeFormType();

        parent::setUp();
    }

    /**
     * Tests the form submit
     *
     * @param string $validationType The validation type for the literal
     *
     * @dataProvider getValidationTypes
     */
    public function testSubmitValidData($validationType)
    {
        $form = $this->factory->create($this->formType);

        $literal = new LiteralAttribute();
        $literal->setValidationType($validationType)
                ->setAllowBlank(false)
                ->setName('name')
                ->setDefaultValue('default');

        $form->setData($literal);

        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($literal, $form->getData());

        $expectedViewComponents = array('type', 'name', 'allow_blank', 'default_value');
        $this->assertViewHasComponents($expectedViewComponents, $form->createView());
    }

    /**
     * Gets validation types as arguments for tests
     *
     * @return array
     */
    public function getValidationTypes()
    {
        return array(
            array(LiteralAttribute::VALIDATION_DATE),
            array(LiteralAttribute::VALIDATION_DATETIME),
            array(LiteralAttribute::VALIDATION_EMAIL),
            array(LiteralAttribute::VALIDATION_FILE),
            array(LiteralAttribute::VALIDATION_IP),
            array(LiteralAttribute::VALIDATION_NUMBER),
            array(LiteralAttribute::VALIDATION_STRING),
            array(LiteralAttribute::VALIDATION_URL)
        );
    }

    /**
     * {@inheritDoc}
     *
     * @return array
     */
    protected function getExtensions()
    {
        $extensions = parent::getExtensions();
        $extensions[] = new CoreExtension();

        $subType = new ChoiceAttributeChoiceFormType();

        $extensions[] = new PreloadedExtension(
            array($subType->getName() => $subType),
            array()
        );

        return $extensions;
    }
}
