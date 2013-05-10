<?php

namespace Tickit\PreferenceBundle\DataFixtures\ORM;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Tickit\PermissionBundle\Entity\UserPermissionValue;
use Tickit\UserBundle\Entity\User;

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
        $users = array($this->getReference('admin-james'), $this->getReference('admin-mark'));

        /** @var User $user */
        foreach ($users as $user) {
            $permissions = $this->getPermissionValues($manager, $user);
            $user->setPermissions($permissions);
        }

        $manager->flush();
    }

    /**
     * Gets a bunch of UserPermissionValues for all permissions
     *
     * @param ObjectManager $manager The object manager
     * @param User          $user    The user to get values for
     *
     * @return ArrayCollection
     */
    protected function getPermissionValues(ObjectManager $manager, User $user)
    {
        $collection = new ArrayCollection();

        $permissions = $manager->getRepository('TickitPermissionBundle:Permission')
                                       ->findAll();

        foreach ($permissions as $permission) {
            $upv = new UserPermissionValue();
            $upv->setUser($user)
                ->setPermission($permission)
                ->setValue(true);

            $collection->add($upv);
        }

        return $collection;
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
