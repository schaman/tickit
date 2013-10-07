<?php

namespace Tickit\ProjectBundle\Tests\Form\Type;

use Doctrine\Common\Collections\ArrayCollection;
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
    public function testSubmitValidData()
    {
        $form = $this->factory->create($this->formType);

        $project = new Project();
        $project->setName(__FUNCTION__)
                ->setAttributes(new ArrayCollection())
                ->setTickets(new ArrayCollection())
                ->setCreated(new \DateTime())
                ->setUpdated(new \DateTime());

        $form->setData($project);

        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($project, $form->getData());

        $expectedViewComponents = array('name', 'attributes');
        $this->assertViewHasComponents($expectedViewComponents, $form->createView());
    }
}