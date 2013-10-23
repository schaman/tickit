<?php

/*
 * 
 * Tickit, an source web based bug management tool.
 * 
 * Copyright (C) 2013  Tickit Project <http://tickit.io>
 * 
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 * 
 */

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
