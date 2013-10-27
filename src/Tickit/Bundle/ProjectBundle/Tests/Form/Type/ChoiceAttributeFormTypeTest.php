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
use Tickit\ProjectBundle\Entity\ChoiceAttribute;
use Tickit\ProjectBundle\Entity\ChoiceAttributeChoice;
use Tickit\ProjectBundle\Form\Type\ChoiceAttributeChoiceFormType;
use Tickit\ProjectBundle\Form\Type\ChoiceAttributeFormType;

/**
 * ChoiceAttributeFormType tests.
 *
 * @package Tickit\ProjectBundle\Tests\Form\Type
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class ChoiceAttributeFormTypeTest extends AbstractFormTypeTestCase
{
    /**
     * Setup
     */
    protected function setUp()
    {
        $this->formType = new ChoiceAttributeFormType();

        parent::setUp();
    }

    /**
     * Tests the form submit
     */
    public function testSubmitValidData()
    {
        $form = $this->factory->create($this->formType);

        $choice1 = new ChoiceAttributeChoice();
        $choice1->setName('choice 1');

        $choice2 = new ChoiceAttributeChoice();
        $choice2->setName('choice 2');

        $choice = new ChoiceAttribute();
        $choice->setAllowMultiple(true)
               ->setChoices(new ArrayCollection(array($choice1, $choice2)))
               ->setExpanded(false)
               ->setName('name')
               ->setAllowBlank(false)
               ->setDefaultValue('default');

        $form->setData($choice);

        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($choice, $form->getData());

        $expectedViewComponents = array('type', 'allow_multiple', 'choices', 'expanded', 'name', 'allow_blank', 'default_value');
        $this->assertViewHasComponents($expectedViewComponents, $form->createView());
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
