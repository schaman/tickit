<?php

namespace Tickit\UserBundle\Tests\Listener;

use Symfony\Component\HttpKernel\HttpKernel;
use Tickit\CoreBundle\Tests\AbstractUnitTest;
use Tickit\UserBundle\Entity\User;
use Tickit\UserBundle\Listener\ActivityListener;

/**
 * ActivityListener tests
 *
 * @package Tickit\UserBundle\Tests\Listener
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
                ->with($this->isInstanceOf('Tickit\UserBundle\Entity\User'));

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
