<?php

/*
 * Tickit, an open source web based bug management tool.
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
 */

namespace Tickit\UserBundle\Tests\Form\EventListener;

use Tickit\ProjectBundle\Form\Event\EntityAttributeFormBuildEvent;
use Tickit\UserBundle\Form\EventListener\FormSubscriber;

/**
 * FormSubscriber tests
 *
 * @package Tickit\UserBundle\Tests\Form\EventListener
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
        $this->assertArrayHasKey('Tickit\UserBundle\Entity\User', $choices);
        $this->assertContains('User', $choices);
    }
}
