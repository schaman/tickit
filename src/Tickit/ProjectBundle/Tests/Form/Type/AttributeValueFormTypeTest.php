<?php

namespace Tickit\ProjectBundle\Tests\Form\Type;

use Symfony\Component\Form\Extension\Core\CoreExtension;
use Symfony\Component\Form\Extension\Validator\ValidatorExtension;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator;
use Tickit\ProjectBundle\Entity\LiteralAttribute;
use Tickit\ProjectBundle\Entity\LiteralAttributeValue;
use Tickit\ProjectBundle\Form\Type\AttributeValueFormType;

/**
 * AttributeValueFormType tests
 *
 * @package Tickit\ProjectBundle\Tests\Form\Type
 * @author  James Halsall <jhalsall@rippleffect.com>
 */
class AttributeValueFormTypeTest extends TypeTestCase
{
    /**
     * Form under test.
     *
     * @var AttributeValueFormType
     */
    private $form;

    /**
     * Setup
     */
    protected function setUp()
    {
        parent::setUp();

        $this->form = new AttributeValueFormType();
    }

    /**
     *
     */
    public function testFormBuildsNotBlankAttributeCorrectly()
    {
        $attribute = new LiteralAttribute();
        $attribute->setValidationType('ip')
                  ->setAllowBlank(false);
        $attributeValue = new LiteralAttributeValue();
        $attributeValue->setAttribute($attribute);

        $form = $this->factory->create($this->form);

        $form->setData($attributeValue);

        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($attributeValue, $form->getData());

        $valueField = $form->get('value');
        $options = $valueField->getConfig()->getOptions();
        $this->assertNotEmpty($options['constraints']);

        $first = array_shift($options['constraints']);
        $this->assertInstanceOf('Symfony\Component\Validator\Constraints\NotBlank', $first);
    }

    /**
     * {@inheritDoc}
     *
     * @return array
     */
    protected function getExtensions()
    {
        return array(new CoreExtension(), new ValidatorExtension(Validation::createValidator()));
    }
}
