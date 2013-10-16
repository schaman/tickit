<?php

namespace Tickit\NotificationBundle\Tests\Controller;

use Tickit\CoreBundle\Tests\AbstractUnitTest;
use Tickit\NotificationBundle\Controller\ApiController;
use Tickit\NotificationBundle\Entity\UserNotification;
use Tickit\UserBundle\Entity\User;

/**
 * ApiController tests
 *
 * @package Tickit\NotificationBundle\Tests\Controller
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class ApiControllerTest extends AbstractUnitTest
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $baseHelper;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $provider;

    /**
     * Setup
     */
    protected function setUp()
    {
        $this->provider = $this->getMockBuilder('\Tickit\NotificationBundle\Provider\NotificationProvider')
                               ->disableOriginalConstructor()
                               ->getMock();

        $this->baseHelper = $this->getMockBaseHelper();
    }
    
    /**
     * Tests the listAction() method
     */
    public function testListActionBuildsCorrectResponse()
    {
        $user = new User();
        $notification1 = new UserNotification();
        $notification1->setMessage('notification 1');

        $notification2 = new UserNotification();
        $notification2->setMessage('notification 2');

        $notifications = array($notification1, $notification2);

        $this->provider->expects($this->once())
                       ->method('findUnreadForUser')
                       ->with($user)
                       ->will($this->returnValue($notifications));

        $this->baseHelper->expects($this->once())
                         ->method('getUser')
                         ->will($this->returnValue($user));

        $decorator = $this->getMockBuilder('\Tickit\CoreBundle\Decorator\DomainObjectDecoratorInterface')
                          ->getMock();

        $decorator->expects($this->exactly(2))
                  ->method('decorate')
                  ->will($this->returnValue(array('notification')));

        $decorator->expects($this->at(0))
                  ->method('decorate')
                  ->with($notification1, array('message', 'createdAt', 'actionUri'));

        $decorator->expects($this->at(1))
                  ->method('decorate')
                  ->with($notification2, array('message', 'createdAt', 'actionUri'));

        $this->baseHelper->expects($this->once())
                         ->method('getObjectDecorator')
                         ->will($this->returnValue($decorator));

        $expectedData = array(
            array('notification'),
            array('notification')
        );

        $response = $this->getController()->listAction();
        $this->assertEquals($expectedData, json_decode($response->getContent(), true));
    }

    /**
     * Gets a new controller instance
     *
     * @return ApiController
     */
    private function getController()
    {
        return new ApiController($this->provider, $this->baseHelper);
    }
}
