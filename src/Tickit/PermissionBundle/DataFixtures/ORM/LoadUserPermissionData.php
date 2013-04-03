<?php

namespace Tickit\PreferenceBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Tickit\PermissionBundle\Entity\UserPermissionValue;

/**
 * Loads default permission records into the database
 *
 * @package Tickit\PreferenceBundle\DataFixtures\ORM
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class LoadUserPermissionData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * Initialises the loading of data
     *
     * @param ObjectManager $manager The object manager
     */
    public function load(ObjectManager $manager)
    {
        $permissions = $manager->getRepository('TickitPermissionBundle:Permission')
                               ->findAll();

        $users = array($this->getReference('admin-james'), $this->getReference('admin-mark'));

        foreach ($users as $user) {
            foreach ($permissions as $permission) {
                $userPermission = new UserPermissionValue();
                $userPermission->setUser($user);
                $userPermission->setPermission($permission);
                $userPermission->setValue(true);
                $manager->persist($userPermission);
            }
        }

        $manager->flush();
    }

    /**
     * Gets the order number for this set of fixtures
     *
     * @return int
     */
    public function getOrder()
    {
        return 12;
    }
}
