<?php

namespace Tickit\Bundle\CoreBundle\Tests\Form\Type\Picker;

use Doctrine\Common\Collections\ArrayCollection;
use Tickit\Bundle\CoreBundle\Form\Type\Picker\AbstractPickerType;
use Tickit\Bundle\CoreBundle\Tests\Form\Type\AbstractFormTypeTestCase;
use Tickit\Bundle\CoreBundle\Tests\Form\Type\Picker\Mock\MockEntity;

/**
 * AbstractPickerType tests
 *
 * @package Tickit\Bundle\CoreBundle\Tests\Form\Type\Picker
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class AbstractPickerTypeTest extends AbstractFormTypeTestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $decorator;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $transformer;

    /**
     * Setup
     */
    protected function setUp()
    {
        parent::setUp();

        $this->decorator = $this->getMock('\Tickit\Component\Decorator\Entity\EntityDecoratorInterface');
        $this->transformer = $this->getMockForAbstractClass(
            '\Tickit\Bundle\CoreBundle\Form\Type\Picker\DataTransformer\AbstractPickerDataTransformer'
        );

        $this->transformer->expects($this->any())
                          ->method('getEntityIdentifier')
                          ->will($this->returnValue('id'));

        $this->formType = $this->getMockForAbstractClass(
            '\Tickit\Bundle\CoreBundle\Form\Type\Picker\AbstractPickerType',
            [$this->decorator, $this->transformer]
        );
    }

    /**
     * Tests submission of a single entity ID
     */
    public function testSingleEntityIdSubmission()
    {
        $form = $this->factory->create(
            $this->formType,
            null,
            ['picker_restriction' => AbstractPickerType::RESTRICTION_SINGLE]
        );
        $entity = new MockEntity(1);

        $this->transformer->expects($this->once())
                          ->method('findEntityByIdentifier')
                          ->with(1)
                          ->will($this->returnValue($entity));

        $form->submit('1');

        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($entity, $form->getData());


    }

    /**
     * Tests form data of 2 entity IDs resolves to correct display names
     */
    public function testDoubleEntitySubmission()
    {
        $form = $this->factory->create($this->formType);

        $entity1 = new MockEntity(1);
        $entity2 = new MockEntity(2);

        $formData = '1,2';
        $expectedData = new ArrayCollection([$entity1, $entity2]);

        $this->transformer->expects($this->exactly(2))
                          ->method('findEntityByIdentifier')
                          ->will($this->onConsecutiveCalls($entity1, $entity2));

        $this->transformer->expects($this->at(0))
                          ->method('findEntityByIdentifier')
                          ->with(1);

        $this->transformer->expects($this->at(1))
                          ->method('findEntityByIdentifier')
                          ->with(2);

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($expectedData, $form->getData());
    }

    /**
     * Tests invalid entity identifier returns no instances
     */
    public function testSubmittedInvalidEntityIdentifiers()
    {
        $form = $this->factory->create($this->formType);

        $this->transformer->expects($this->once())
                          ->method('findEntityByIdentifier')
                          ->with(1)
                          ->will($this->returnValue(null));

        $formData = '1';

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertEquals(new ArrayCollection(), $form->getData());
    }

    /**
     * Tests the form view displays a single entity instance correctly
     */
    public function testDisplaySingleEntityInstance()
    {
        $form = $this->factory->create(
            $this->formType,
            null,
            ['picker_restriction' => AbstractPickerType::RESTRICTION_SINGLE]
        );

        $entity = new MockEntity(1);
        $form->setData($entity);

        $this->decorator->expects($this->once())
                        ->method('decorate')
                        ->with($entity)
                        ->will($this->returnValue('display name'));

        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($entity, $form->getData());

        $formView = $form->createView();

        $this->assertObjectHasAttribute('displayValues', $formView);
        $this->assertEquals('display name', $formView->displayValues);
    }

    /**
     * Tests the form view displays a collection of entities correctly
     */
    public function testDisplayEntityCollection()
    {
        $form = $this->factory->create($this->formType);

        $entity1 = new MockEntity(1);
        $entity2 = new MockEntity(2);
        $entity3 = new MockEntity(3);

        $formData = new ArrayCollection([$entity1, $entity2, $entity3]);
        $form->setData($formData);

        $this->decorator->expects($this->exactly(3))
                        ->method('decorate')
                        ->will($this->onConsecutiveCalls('display name 1', 'display name 2', 'display name 3'));

        $this->decorator->expects($this->at(0))
                        ->method('decorate')
                        ->with($entity1);

        $this->decorator->expects($this->at(1))
                        ->method('decorate')
                        ->with($entity2);

        $this->decorator->expects($this->at(2))
                        ->method('decorate')
                        ->with($entity3);

        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($formData, $form->getData());

        $formView = $form->createView();

        $this->assertObjectHasAttribute('displayValues', $formView);
        $this->assertEquals('display name 1,display name 2,display name 3', $formView->displayValues);
    }
}
