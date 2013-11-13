<?php

/*
 * Tickit, an open source web based bug management tool.
 * 
 * Copyright (C) 2013  Tickit Project <http://tickit.io>
 * 
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Tickit\Bundle\UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Tickit\Bundle\UserBundle\Entity\User;
use DateTime;

/**
 * Loads default users into the application
 */
class LoadUserData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * Initiates the data loading
     *
     * @param \Doctrine\Common\Persistence\ObjectManager $manager
     *
     * @return void
     */
    public function load(ObjectManager $manager)
    {
        $admin1 = new User();
        $admin1->setUsername('james');
        $admin1->setPlainPassword('password');
        $admin1->setSuperAdmin(true);
        $admin1->setEmail('james.t.halsall@googlemail.com');
        $admin1->setForename('James');
        $admin1->setSurname('Halsall');
        $admin1->setEnabled(true);
        $admin1->setLastActivity(new DateTime());
        $admin1->addRole('ROLE_SUPER_ADMIN');

        $manager->persist($admin1);
        $this->addReference('admin-james', $admin1);

        $admin2 = new User();
        $admin2->setUsername('mark');
        $admin2->setPlainPassword('password');
        $admin2->setSuperAdmin(true);
        $admin2->setEmail('mark@89allport.co.uk');
        $admin2->setForename('Mark');
        $admin2->setSurname('Wilson');
        $admin2->setEnabled(true);
        $admin2->setLastActivity(new DateTime());
        $admin2->addRole('ROLE_SUPER_ADMIN');

        $manager->persist($admin2);
        $this->addReference('admin-mark', $admin2);

        $developer = new User();
        $developer->setUsername('developer');
        $developer->setPlainPassword('password');
        $developer->setSuperAdmin(false);
        $developer->setEmail('developer@gettickit.com');
        $developer->setForename('Tickit');
        $developer->setSurname('Developer');
        $developer->setEnabled(true);
        $developer->setLastActivity(new DateTime());

        $manager->persist($developer);
        $this->addReference('developer', $developer);

        //add other users for development environment here

        $manager->flush();
    }

    /**
     * Returns the order number for this set of fixtures
     *
     * @return int
     */
    public function getOrder()
    {
        return 2;
    }
}
