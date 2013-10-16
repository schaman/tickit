<?php

namespace Tickit\CoreBundle\Tests\Event;

use Tickit\CoreBundle\Event\EntityModifiedEvent;

/**
 * EntityModifiedEvent tests
 *
 * @package Tickit\CoreBundle\Tests\Event
 * @author  James Halsall <james.t.halsal@googlemail.com>
 */
class EntityModifiedEventTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Dummy entity object
     *
     * @var \stdClass
     */
    private $entity;

    /**
     * Dummy original entity object
     *
     * @var \stdClass
     */
    private $originalEntity;

    /**
     * Event under test
     *
     * @var EntityModifiedEvent
     */
    private $event;

    /**
     * Setup
     */
    protected function setUp()
    {
        $this->entity = new \stdClass();
        $this->originalEntity = new \stdClass();
        $this->event = new EntityModifiedEvent($this->entity, $this->originalEntity);
    }

    /**
     * Tests the __construct() method
     */
    public function testConstructSetsUpEventCorrectly()
    {
        $this->assertSame($this->entity, $this->event->getEntity());
        $this->assertSame($this->originalEntity, $this->event->getOriginalEntity());
    }
}
