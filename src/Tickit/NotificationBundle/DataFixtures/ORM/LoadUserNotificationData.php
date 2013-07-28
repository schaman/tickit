<?php

namespace Tickit\NotificationBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Tickit\NotificationBundle\Entity\UserNotification;

/**
 * Loads user notification data.
 *
 * @package Tickit\NotificationBundle\DataFixtures\ORM
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class LoadUserNotificationData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager The object manager
     *
     * @return void
     */
    public function load(ObjectManager $manager)
    {
        $adminUser = $this->getReference('admin-james');

        $notification = new UserNotification();
        $notification->setRecipient($adminUser)
                     ->setMessage('Test message')
                     ->setActionUri('/dashboard');

        $notification2 = new UserNotification();
        $notification2->setRecipient($adminUser)
                      ->setMessage('Test message 2')
                      ->setActionUri('/dashboard');

        $manager->persist($notification);
        $manager->persist($notification2);
        $manager->flush();
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 20;
    }
}
