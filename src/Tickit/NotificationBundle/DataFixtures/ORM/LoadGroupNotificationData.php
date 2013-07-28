<?php

namespace Tickit\NotificationBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Tickit\NotificationBundle\Entity\GroupNotification;

/**
 * Loads group notification data.
 *
 * @package Tickit\NotificationBundle\DataFixtures\ORM
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class LoadGroupNotificationData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager The object manager
     */
    public function load(ObjectManager $manager)
    {
        $adminGroup = $this->getReference('admin-group');

        $groupNotification = new GroupNotification();
        $groupNotification->setRecipient($adminGroup)
                          ->setActionUri('/dashboard')
                          ->setMessage('Test group message');

        $groupNotification2 = new GroupNotification();
        $groupNotification2->setRecipient($adminGroup)
                           ->setActionUri('/dashboard')
                           ->setMessage('Test group message 2');

        $manager->persist($groupNotification);
        $manager->persist($groupNotification2);

        $manager->flush();
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 21;
    }

}
