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

namespace Tickit\Bundle\UserBundle\Tests\Listener;

use Symfony\Component\HttpKernel\HttpKernel;
use Tickit\Bundle\CoreBundle\Tests\AbstractUnitTest;
use Tickit\Bundle\UserBundle\Entity\User;
use Tickit\Bundle\UserBundle\Listener\ActivityListener;

/**
 * ActivityListener tests
 *
 * @package Tickit\Bundle\UserBundle\Tests\Listener
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class ActivityListenerTest extends AbstractUnitTest
{
    /**
     * Tests the onCoreController() method
     */
    public function testOnCoreControllerUpdatesUserActivityForMasterRequest()
    {
        $securityContext = $this->getMockSecurityContext();
        $token = $this->getMockUsernamePasswordToken();
        $manager = $this->getMockEntityManager();

        $user = new User();
        $user->setUsername('username');

        $token->expects($this->once())
              ->method('getUser')
              ->will($this->returnValue($user));

        $securityContext->expects($this->once())
                        ->method('getToken')
                        ->will($this->returnValue($token));

        $manager->expects($this->once())
                ->method('persist')
                ->with($this->isInstanceOf('Tickit\Bundle\UserBundle\Entity\User'));

        $manager->expects($this->once())
                ->method('flush')
                ->with();

        $listener = new ActivityListener($securityContext, $manager);

        $event = $this->getMockFilterControllerEvent();

        $event->expects($this->once())
              ->method('getRequestType')
              ->will($this->returnValue(HttpKernel::MASTER_REQUEST));

        $listener->onCoreController($event);
    }

    /**
     * Tests the onCoreController() method
     */
    public function testOnCoreControllerDoesNotUpdateUserActivityForNonMasterRequest()
    {
        $securityContext = $this->getMockSecurityContext();
        $manager = $this->getMockEntityManager();

        $event = $this->getMockFilterControllerEvent();
        $event->expects($this->once())
              ->method('getRequestType')
              ->will($this->returnValue(HttpKernel::SUB_REQUEST));

        $securityContext->expects($this->exactly(0))
                        ->method('getToken');

        $manager->expects($this->exactly(0))
                ->method('persist');

        $manager->expects($this->exactly(0))
                ->method('flush');

        $listener = new ActivityListener($securityContext, $manager);
        $listener->onCoreController($event);
    }

    /**
     * Returns mock FilterControllerEvent
     *
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function getMockFilterControllerEvent()
    {
        return $this->getMockBuilder('\Symfony\Component\HttpKernel\Event\FilterControllerEvent')
                    ->disableOriginalConstructor()
                    ->getMock();
    }
}
