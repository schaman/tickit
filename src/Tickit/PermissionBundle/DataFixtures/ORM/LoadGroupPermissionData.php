<?php

namespace Tickit\PreferenceBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Tickit\PermissionBundle\Entity\Permission;
use Tickit\PermissionBundle\Entity\DefaultGroupPermissionValue;
use Tickit\PermissionBundle\Entity\GroupPermissionValue;

/**
 * Loads default group permission value records into the database
 *
 * @package Tickit\PreferenceBundle\DataFixtures\ORM
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class LoadGroupPermissionData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * The object manager
     *
     * @var ObjectManager
     */
    protected $manager;

    /**
     * Initialises the loading of data
     *
     * @param ObjectManager $manager The object manager
     */
    public function load(ObjectManager $manager)
    {
        $this->manager = $manager;

        $groups = array(
            $this->getReference('admin-group'),
            $this->getReference('dev-group'),
            $this->getReference('client-group'),
            $this->getReference('test-group')
        );

        $permissions = $manager->getRepository('TickitPermissionBundle:Permission')
                               ->findAll();

        $this->loadGroupPermissionValues($permissions, $groups);
    }

    /**
     * Gets the order number for this set of fixtures
     *
     * @return int
     */
    public function getOrder()
    {
        return 11;
    }

    /**
     * Loads DefaultGroupPermissionValue and GroupPermissionValue records into the database
     *
     * @param array $permissions An array containing all system permissions
     * @param array $groups      An array containing all user groups in the system
     *
     * @todo This needs to be refactored to suit the ManyToMany direct relationship
     */
    protected function loadGroupPermissionValues(array $permissions, array $groups)
    {
        /** @var \Tickit\PermissionBundle\Entity\Permission $permission */
        foreach ($permissions as $permission) {
            /** @var \Tickit\UserBundle\Entity\Group $group */
            foreach ($groups as $group) {

                $value = true; //todo: this needs to change based on group

                $defaultGroupPermValue = new DefaultGroupPermissionValue();
                $defaultGroupPermValue->setGroup($group);
                $defaultGroupPermValue->setPermission($permission);
                $defaultGroupPermValue->setValue($value);

                $this->manager->persist($defaultGroupPermValue);

                $groupPermValue = new GroupPermissionValue();
                $groupPermValue->setGroup($group);
                $groupPermValue->setPermission($permission);
                $groupPermValue->setValue($value);

                $this->manager->persist($groupPermValue);
            }
        }

        $this->manager->flush();
    }
}
