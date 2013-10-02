<?php

namespace Tickit\UserBundle\Tests\Event;

use MyProject\Proxies\__CG__\OtherProject\Proxies\__CG__\stdClass;
use Tickit\CoreBundle\Event\EntityEvent;

/**
 * EntityEvent tests
 *
 * @package Tickit\CoreBundle\Tests\Event
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class EntityEventTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Dummy entity object
     *
     * @var stdClass
     */
    private $entity;

    /**
     * Event under test
     *
     * @var EntityEvent
     */
    private $event;

    /**
     * Setup
     */
    protected function setUp()
    {
        $this->entity = new \stdClass();
        $this->event = new EntityEvent($this->entity);
    }

    /**
     * Tests the __construct() method
     */
    public function testConstructSetsUpEventCorrectly()
    {
        $this->assertSame($this->entity, $this->event->getEntity());
    }

    /**
     * Tests the setUser() method
     */
    public function testSetEntitySetsCorrectValue()
    {
        $entity = new \stdClass();
        $this->event->setEntity($entity);

        $this->assertSame($entity, $this->event->getEntity());
    }
}
