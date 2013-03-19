<?php

namespace Tickit\CoreBundle\Tests\Event;

use Tickit\CoreBundle\Event\AbstractBeforeDeleteEvent;

/**
 * Tests for the AbstractBeforeDeleteEvent object
 *
 * @package Tickit\CoreBundle\Tests\Event
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class AbstractBeforeDeleteEventTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Ensures that the AbstractBeforeDeleteEvent object is a descendant of the event class
     *
     * @return void
     */
    public function testAbstractIsAnEvent()
    {
        $event = $this->getMockForAbstractClass('\Tickit\CoreBundle\Event\AbstractBeforeDeleteEvent');
        $this->assertInstanceOf('Symfony\Component\EventDispatcher\Event', $event);
    }

    /**
     * Tests the isVetoed() method
     *
     * Ensures that the method returns true and false in the correct scenarios
     *
     * @return void
     */
    public function testIsVetoedReturnsCorrectly()
    {
        /** @var AbstractBeforeDeleteEvent $event  */
        $event = $this->getMockForAbstractClass('\Tickit\CoreBundle\Event\AbstractBeforeDeleteEvent');

        $this->assertFalse($event->isVetoed(), 'isVeoted() correctly returns false initially');
        $event->veto();
        $this->assertTrue($event->isVetoed(), 'isVetoed() correctly returns true after calling veto()');
    }
}
