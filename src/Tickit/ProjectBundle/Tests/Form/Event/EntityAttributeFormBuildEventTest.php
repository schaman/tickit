<?php


namespace Tickit\ProjectBundle\Tests\Form\Event;

use Tickit\ProjectBundle\Form\Event\EntityAttributeFormBuildEvent;
use Tickit\ProjectBundle\Tests\Form\Event;

/**
 * EntityAttributeFormBuildEvent tests
 *
 * @package Tickit\ProjectBundle\Tests\Form\Event
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class EntityAttributeFormBuildEventTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Tests the getEntityChoices() method
     *
     * @return void
     */
    public function testGetEntityChoicesReturnsArrayInCorrectFormat()
    {
        $event = new EntityAttributeFormBuildEvent();
        $event->addEntityChoice('className', 'value name');
        $event->addEntityChoice('className2', 'value name 2');

        $expected = array(
            'className' => 'value name',
            'className2' => 'value name 2'
        );

        $this->assertEquals($expected, $event->getEntityChoices());
    }
}
