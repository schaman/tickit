<?php

namespace Tickit\ProjectBundle\Tests\Form\Type;

use Symfony\Component\Form\Extension\Core\CoreExtension;
use Symfony\Component\Form\PreloadedExtension;
use Tickit\CoreBundle\Tests\Form\Type\AbstractFormTypeTestCase;
use Tickit\ProjectBundle\Entity\ChoiceAttributeChoice;
use Tickit\ProjectBundle\Form\Type\ChoiceAttributeChoiceFormType;

/**
 * ChoiceAttributeChoiceFormType tests.
 *
 * @package Tickit\ProjectBundle\Tests\Form\Type
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class ChoiceAttributeChoiceFormTypeTest extends AbstractFormTypeTestCase
{
    /**
     * Setup
     */
    protected function setUp()
    {
        $this->formType = new ChoiceAttributeChoiceFormType();

        parent::setUp();
    }

    /**
     * Tests the form submit
     */
    public function testSubmitValidData()
    {
        $form = $this->factory->create($this->formType);

        $choice = new ChoiceAttributeChoice();
        $choice->setName('choice 1');

        $form->setData($choice);

        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($choice, $form->getData());

        $expectedViewComponents = array('name');
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
