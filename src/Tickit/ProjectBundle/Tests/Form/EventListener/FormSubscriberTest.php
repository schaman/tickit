<?php

namespace Tickit\ProjectBundle\Tests\Form\EventListener;

use Tickit\ProjectBundle\Form\Event\EntityAttributeFormBuildEvent;
use Tickit\ProjectBundle\Form\EventListener\FormSubscriber;

/**
 * FormSubscriber tests
 *
 * @package Tickit\ProjectBundle\Tests\Form\EventListener
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class FormSubscriberTest extends \PHPUnit_Framework_TestCase
{
    /**
     * The subscriber under test
     *
     * @var FormSubscriber
     */
    private $subscriber;

    /**
     * Setup
     */
    protected function setUp()
    {
        $this->subscriber = new FormSubscriber();
    }

    /**
     * Tests the event hook
     */
    public function testOnEntityAttributeFormBuild()
    {
        $event = new EntityAttributeFormBuildEvent();

        $this->subscriber->onEntityAttributeFormBuild($event);

        $choices = $event->getEntityChoices();
        $this->assertNotEmpty($choices);
        $this->assertArrayHasKey('Tickit\ProjectBundle\Entity\Project', $choices);
        $this->assertContains('Project', $choices);
    }
}
