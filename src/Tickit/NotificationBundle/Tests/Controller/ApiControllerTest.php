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

        $expectedData = [['notification'], ['notification']];
        $decorator = $this->getMockObjectCollectionDecorator();
        $this->trainBaseHelperToReturnObjectCollectionDecorator($decorator);
        $this->trainObjectCollectionDecoratorToExpectNotificationCollection($decorator, $notifications, $expectedData);

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

    private function trainBaseHelperToReturnObjectCollectionDecorator(\PHPUnit_Framework_MockObject_MockObject $decorator)
    {
        $this->baseHelper->expects($this->once())
                         ->method('getObjectCollectionDecorator')
                         ->will($this->returnValue($decorator));
    }

    private function trainObjectCollectionDecoratorToExpectNotificationCollection(
        \PHPUnit_Framework_MockObject_MockObject $objectDecorator,
        array $notifications,
        array $returnData
    ) {
        $objectDecorator->expects($this->once())
                        ->method('decorate')
                        ->with(
                            $notifications,
                            ['message', 'createdAt', 'actionUri']
                        )
                        ->will($this->returnValue($returnData));
    }
}
