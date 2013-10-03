<?php

namespace Tickit\NotificationBundle\Tests\Manager;

use Tickit\CoreBundle\Tests\AbstractUnitTest;
use Tickit\NotificationBundle\Entity\UserNotification;
use Tickit\NotificationBundle\Factory\NotificationFactory;
use Tickit\NotificationBundle\Manager\NotificationManager;

/**
 * NotificationManager tests
 *
 * @package Tickit\NotificationBundle\Tests\Manager
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
