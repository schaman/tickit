<?php

namespace Tickit\Bundle\ProjectBundle\Tests\Form\Type;

use Symfony\Component\Form\PreloadedExtension;
use Tickit\Bundle\ClientBundle\Form\Type\Picker\ClientPickerType;
use Tickit\Bundle\CoreBundle\Tests\Form\Type\AbstractFormTypeTestCase;
use Tickit\Bundle\ProjectBundle\Form\Type\FilterFormType;
use Tickit\Bundle\UserBundle\Form\Type\Picker\UserPickerType;
use Tickit\Component\Model\Project\Project;

/**
 * FilterFormType tests
 *
 * @package Tickit\Bundle\ProjectBundle\Tests\Form\Type
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class FilterFormTypeTest extends AbstractFormTypeTestCase
{
    /**
     * Setup
     */
    protected function setUp()
    {
        parent::setUp();

        $this->formType = new FilterFormType();
    }

    /**
     * Tests the form submit
     */
    public function testSubmitValidData()
    {
        $form = $this->factory->create($this->formType);

        $data = [
            'name' => 'project-name',
            'owner' => '100,200',
            'status' => Project::STATUS_ARCHIVED,
            'client' => '120, 123'
        ];

        $form->submit($data);

        $expectedData = [
            'name' => 'project-name',
            'owner' => new \stdClass(),
            'status' => Project::STATUS_ARCHIVED,
            'client' => new \stdClass()
        ];

        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($expectedData, $form->getData());

        $expectedFields = ['name', 'owner', 'status', 'client'];
        $this->assertViewHasComponents($expectedFields, $form->createView());
    }

    /**
     * Gets form extensions
     *
     * @return array
     */
    protected function getExtensions()
    {
        $extensions = parent::getExtensions();

        $decorator = $this->getMock('\Tickit\Component\Decorator\Entity\EntityDecoratorInterface');
        $transformer = $this->getMockForAbstractClass(
                                'Tickit\Bundle\CoreBundle\Form\Type\Picker\DataTransformer\AbstractPickerDataTransformer',
                                [],
                                '',
                                false,
                                false,
                                true,
                                ['transform', 'reverseTransform']
                            );

        $transformer->expects($this->any())
                    ->method('transform')
                    ->will($this->returnValue('transformed value'));

        $transformer->expects($this->any())
                    ->method('reverseTransform')
                    ->will($this->returnValue(new \stdClass()));

        $userPicker = new UserPickerType($decorator, $transformer);
        $clientPicker = new ClientPickerType($decorator, $transformer);

        $extensions[] = new PreloadedExtension(
            [$userPicker->getName() => $userPicker, $clientPicker->getName() => $clientPicker],
            []
        );

        return $extensions;
    }
}
 