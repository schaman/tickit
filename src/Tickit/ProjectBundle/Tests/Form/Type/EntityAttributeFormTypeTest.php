<?php

namespace Tickit\ProjectBundle\Tests\Form\Type;

use Symfony\Component\EventDispatcher\Tests\EventDispatcherTest;
use Tickit\CoreBundle\Tests\Form\Type\AbstractFormTypeTestCase;
use Tickit\ProjectBundle\Entity\EntityAttribute;
use Tickit\ProjectBundle\Form\Event\EntityAttributeFormBuildEvent;
use Tickit\ProjectBundle\Form\Type\EntityAttributeFormType;

/**
 * EntityAttributeFormType tests.
 *
 * @package Tickit\ProjectBundle\Tests\Form\Type
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class EntityAttributeFormTypeTest extends AbstractFormTypeTestCase
{
    /**
     * Setup
     */
    protected function setUp()
    {
        $event = new EntityAttributeFormBuildEvent();
        $event->addEntityChoice('\Tickit\ProjectBundle\Entity\Project', 'Project');

        $dispatcher = $this->getMock('\Symfony\Component\HttpKernel\Tests\Fixtures\TestEventDispatcher');

        $dispatcher->expects($this->any())
                   ->method('dispatch')
                   ->will($this->returnValue($event));

        $this->formType = new EntityAttributeFormType($dispatcher);

        parent::setUp();
    }

    /**
     * Tests the form submit
     */
    public function testSubmitValidData()
    {
        $form = $this->factory->create($this->formType);

        $entity = new EntityAttribute();
        $entity->setEntity('\Tickit\ProjectBundle\Entity\Project')
               ->setName('name')
               ->setDefaultValue('default')
               ->setAllowBlank(true);

        $form->setData($entity);

        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($entity, $form->getData());

        $expectedViewComponents = array('type', 'entity', 'name', 'allow_blank', 'default_value');
        $this->assertViewHasComponents($expectedViewComponents, $form->createView());
    }
}
