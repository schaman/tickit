<?php

namespace Tickit\ProjectBundle\Tests\Form\Type;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Form\Extension\Core\CoreExtension;
use Symfony\Component\Form\PreloadedExtension;
use Tickit\CoreBundle\Tests\Form\Type\AbstractFormTypeTestCase;
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
