<?php

namespace Tickit\ProjectBundle\Tests\Form\Type;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Form\Extension\Core\CoreExtension;
use Symfony\Component\Form\PreloadedExtension;
use Tickit\CoreBundle\Tests\Form\Type\AbstractFormTypeTestCase;
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
