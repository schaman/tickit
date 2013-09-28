<?php

namespace Tickit\NotificationBundle\Tests\Provider;
use Tickit\NotificationBundle\Entity\UserNotification;
use Tickit\NotificationBundle\Provider\NotificationProvider;
use Tickit\UserBundle\Entity\User;


/**
 * NotificationProviderTest tests
 *
 * @package Tickit\NotificationBundle\Tests\Provider
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class NotificationProviderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * The provider under test
     *
     * @var NotificationProvider
     */
    private $provider;

    /**
     * Setup
     */
    protected function setUp()
    {
        $repo = $this->getMockBuilder('Tickit\NotificationBundle\Entity\Repository\UserNotificationRepository')
                     ->disableOriginalConstructor()
                     ->getMock();

        $repo->expects($this->any())
             ->method('findUnreadForUser')
             ->will($this->returnValue(array(new UserNotification(), new UserNotification())));

        $this->provider = new NotificationProvider($repo, 20);
    }

    /**
     * Tests the getUserNotificationRepository() method
     */
    public function testGetUserNotificationRepositoryReturnsCorrectValue()
    {
        $repo = $this->provider->getUserNotificationRepository();

        $this->assertInstanceOf('Tickit\NotificationBundle\Entity\Repository\UserNotificationRepository', $repo);
    }

    /**
     * Tests the findUnreadForUser() method
     */
    public function testFindUnreadForUserPassesCorrectValues()
    {
        $user = new User();
        $user->setUsername('username');

        $notifications = $this->provider->findUnreadForUser($user);

        $this->assertInternalType('array', $notifications);
        $this->assertCount(2, $notifications);
        $this->assertContainsOnlyInstancesOf('Tickit\NotificationBundle\Entity\UserNotification', $notifications);
    }
}
