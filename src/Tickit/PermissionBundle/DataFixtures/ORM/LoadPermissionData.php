<?php

namespace Tickit\PreferenceBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Tickit\PermissionBundle\Entity\Permission;

/**
 * Loads default permission records into the database
 *
 * @package Tickit\PreferenceBundle\DataFixtures\ORM
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class LoadPermissionData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * Initialises the loading of data
     *
     * @param ObjectManager $manager The object manager
     */
    public function load(ObjectManager $manager)
    {
        $permissions = array(
            // UserBundle related permissions
            'users.view' => 'View users',
            'users.create' => 'Create new users',
            'users.edit' => 'Edit existing users',
            'users.delete' => 'Delete existing users',
            // TeamBundle related permissions
            'teams.view' => 'View teams',
            'teams.create' => 'Create new teams',
            'teams.edit' => 'Edit existing teams',
            'teams.delete' => 'Delete existing teams',
            // TicketBundle related permissions
            'tickets.attachments.create' => 'Create ticket attachments',
            'tickets.attachments.delete' => 'Delete ticket attachments',
            'tickets.statuses.view' => 'View ticket statuses',
            'tickets.statuses.create' => 'Create new ticket statuses',
            'tickets.statuses.edit' => 'Edit existing ticket statuses',
            'tickets.statuses.delete' => 'Delete existing ticket statuses',
            // ProjectBundle related permissions
            'projects.view' => 'View projects',
            'projects.create' => 'Create new projects',
            'projects.edit' => 'Edit existing projects',
            'projects.delete' => 'Delete existing projects',
            'projects.close' => 'Close existing projects',
            // PermissionBundle related permissions
            'permissions.users.manage' => 'Manage user permissions',
            'permissions.users.clone' => 'Clone user permissions',
            'permissions.groups.manage' => 'Manage group permissions',
            'permissions.groups.manage_defaults' => 'Manage default group permissions'
        );

        foreach ($permissions as $systemName => $name) {
            $permission = new Permission();
            $permission->setName($name);
            $permission->setSystemName($systemName);
            $manager->persist($permission);
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
        return 10;
    }
}
