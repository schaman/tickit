<?php

namespace Tickit\ProjectBundle\Tests\Form\Type;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Form\PreloadedExtension;
use Tickit\ClientBundle\Entity\Client;
use Tickit\ClientBundle\Form\Type\Picker\ClientPickerType;
use Tickit\CoreBundle\Tests\Form\Type\AbstractFormTypeTestCase;
use Tickit\ProjectBundle\Entity\Project;
use Tickit\ProjectBundle\Form\Type\ProjectFormType;

/**
 * ProjectFormType test
 *
 * @package Tickit\ProjectBundle\Tests\Form\Type
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class ProjectFormTypeTest extends AbstractFormTypeTestCase
{
    /**
     * Set up
     */
    protected function setUp()
    {
        parent::setUp();

        $mockAttributeForm = $this->getMockBuilder('Tickit\ProjectBundle\Form\Type\AttributeValueFormType')
                                  ->disableOriginalConstructor()
                                  ->getMock();

        $this->formType = new ProjectFormType($mockAttributeForm);
    }

    /**
     * Tests the form submit
     *
     * @return void
     */
    public function testValidFormData()
    {
        $form = $this->factory->create($this->formType);

        $project = new Project();
        $project->setName(__FUNCTION__)
                ->setAttributes(new ArrayCollection())
                ->setTickets(new ArrayCollection())
                ->setCreated(new \DateTime())
                ->setClient(new Client())
                ->setUpdated(new \DateTime());

        $form->setData($project);

        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($project, $form->getData());

        $expectedViewComponents = array('name', 'attributes', 'client');
        $this->assertViewHasComponents($expectedViewComponents, $form->createView());
    }

    /**
     * @return array
     */
    protected function getExtensions()
    {
        $extensions = parent::getExtensions();

        $decorator = $this->getMockBuilder('Tickit\ClientBundle\Decorator\ClientEntityNameDecorator')
                          ->disableOriginalConstructor()
                          ->getMock();

        $transformer = $this->getMockBuilder(
            'Tickit\ClientBundle\Form\Type\Picker\DataTransformer\ClientPickerDataTransformer'
        )
        ->disableOriginalConstructor()
        ->getMock();

        $transformer->expects($this->any())
                    ->method('transform')
                    ->will($this->returnValue(1));

        $transformer->expects($this->any())
                    ->method('reverseTransform')
                    ->will($this->returnValue(new Client()));

        $decorator->expects($this->any())
                  ->method('convert')
                  ->will($this->returnValue('decorated client'));

        $clientPicker = new ClientPickerType($decorator, $transformer);

        $extensions[] = new PreloadedExtension([$clientPicker->getName() => $clientPicker], []);

        return $extensions;
    }
}
