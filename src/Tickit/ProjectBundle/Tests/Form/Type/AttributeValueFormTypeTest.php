<?php

namespace Tickit\ProjectBundle\Tests\Form\Type;

use Symfony\Bridge\Doctrine\Form\DoctrineOrmExtension;
use Symfony\Bridge\Doctrine\Test\DoctrineTestHelper;
use Symfony\Component\Form\Extension\Core\CoreExtension;
use Symfony\Component\Form\Extension\Validator\ValidatorExtension;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\Validator\Validation;
use Tickit\ProjectBundle\Entity\EntityAttribute;
use Tickit\ProjectBundle\Entity\EntityAttributeValue;
use Tickit\ProjectBundle\Entity\LiteralAttribute;
use Tickit\ProjectBundle\Entity\LiteralAttributeValue;
use Tickit\ProjectBundle\Entity\Project;
use Tickit\ProjectBundle\Form\Type\AttributeValueFormType;

/**
 * AttributeValueFormType tests
 *
 * @package Tickit\ProjectBundle\Tests\Form\Type
 * @author  James Halsall <james.t.halsall@googlemail.com>
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
     * Ensures that a "not blank" attribute is built correctly
     *
     * @return void
     */
    public function testFormBuildsNotBlankAttributeCorrectly()
    {
        $attributeValue = $this->getLiteralAttributeValue(LiteralAttribute::VALIDATION_IP, false);

        $form = $this->factory->create($this->form);

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
     * @dataProvider getLiteralAttributeData
     *
     * @return void
     */
    public function testFormBuildsLiteralAttributesCorrectly($validationType, $expectedConstraintClass)
    {
        $attributeValue = $this->getLiteralAttributeValue($validationType);

        $form = $this->factory->create($this->form);
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
        $attribute->setEntity('Tickit\ProjectBundle\Entity\Project');

        $attributeValue = new EntityAttributeValue();
        $attributeValue->setAttribute($attribute)
                       ->setValue(1)
                       ->setProject(new Project());

        $form = $this->factory->create($this->form);
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
     * Gets test data for testFormBuildsLiteralAttributesCorrectly()
     *
     * @return array
     */
    public function getLiteralAttributeData()
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
                LiteralAttribute::VALIDATION_STRING,
                'Symfony\Component\Validator\Constraints\Type'
            )
        );
    }

    /**
     * {@inheritDoc}
     *
     * @return array
     */
    protected function getExtensions()
    {
        $em = DoctrineTestHelper::createTestEntityManager();

        $manager = $this->getMock('Doctrine\Common\Persistence\ManagerRegistry');

        $manager->expects($this->any())
                ->method('getManager')
                ->will($this->returnValue($em));

        $manager->expects($this->any())
                ->method('getManagerForClass')
                ->will($this->returnValue($em));

        return array(
            new CoreExtension(),
            new ValidatorExtension(Validation::createValidator()),
            new DoctrineOrmExtension($manager)
        );
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
}
