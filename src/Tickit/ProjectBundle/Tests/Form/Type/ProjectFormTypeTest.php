<?php

namespace Tickit\ProjectBundle\Tests\Form\Type;

use Symfony\Component\Form\Test\TypeTestCase;
use Tickit\ProjectBundle\Entity\Project;
use Tickit\ProjectBundle\Form\Type\ProjectFormType;

/**
 * ProjectFormType test
 *
 * @package Tickit\ProjectBundle\Tests\Form\Type
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class ProjectFormTypeTest extends TypeTestCase
{
    /**
     * Tests the form submit
     *
     * @return void
     */
    public function testSubmitValidData()
    {
        $data = array(
            //TODO: add the data
        );

        $mockAttributeForm = $this->getMockBuilder('Tickit\ProjectBundle\Form\Type\AttributeValueFormType')
                                  ->disableOriginalConstructor()
                                  ->getMock();

        $type = new ProjectFormType($mockAttributeForm);
        $form = $this->factory->create($type);

        $project = new Project();
        // TODO: set values on $project

        $form->setData($project);

        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($project, $form->getData());

        // TODO: assert view components
    }
}
