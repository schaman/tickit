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

namespace Tickit\Bundle\NotificationBundle\Tests\Manager;

use Tickit\Bundle\CoreBundle\Tests\AbstractUnitTest;
use Tickit\Bundle\NotificationBundle\Entity\UserNotification;
use Tickit\Bundle\NotificationBundle\Factory\NotificationFactory;
use Tickit\Bundle\NotificationBundle\Manager\NotificationManager;

/**
 * NotificationManager tests
 *
 * @package Tickit\Bundle\NotificationBundle\Tests\Manager
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class NotificationManagerTest extends AbstractUnitTest
{
    /**
     * The notification factory dependency
     *
     * @var NotificationFactory
     */
    private $factory;

    /**
     * Setup
     */
    protected function setUp()
    {
        $this->factory = new NotificationFactory();
    }
    
    /**
     * Tests the create() method
     */
    public function testCreatePersistsAndFlushesNotification()
    {
        $notification = new UserNotification();

        $entityManager = $this->getMockEntityManager();
        $entityManager->expects($this->once())
                      ->method('persist')
                      ->with($notification);

        $entityManager->expects($this->once())
                      ->method('flush');

        $manager = new NotificationManager($entityManager, $this->factory);
        $manager->create($notification);
    }

    /**
     * Tests the getFactory() method
     */
    public function testGetFactoryReturnsCorrectInstance()
    {
        $entityManager = $this->getMockEntityManager();
        $manager = new NotificationManager($entityManager, $this->factory);

        $this->assertSame($this->factory, $manager->getFactory());
    }
}
