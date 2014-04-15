<?php

/*
 * Tickit, an open source web based bug management tool.
 *
 * Copyright (C) 2014  Tickit Project <http://tickit.io>
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
use Faker\Factory;
use Tickit\Component\Model\User\User;

/**
 * Loads default users into the application
 *
 * @package Tickit\Bundle\UserBundle\DataFixtures\ORM
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class LoadUserData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * Initiates the data loading
     *
     * @param ObjectManager $manager The object manager
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
        $admin1->setLastActivity(new \DateTime());
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
        $admin2->setLastActivity(new \DateTime());
        $admin2->addRole('ROLE_SUPER_ADMIN');

        $manager->persist($admin2);
        $this->addReference('admin-mark', $admin2);

        $admin3 = new User();
        $admin3->setUsername('stuart');
        $admin3->setPlainPassword('password');
        $admin3->setSuperAdmin(true);
        $admin3->setEmail('stuart.rayson@me.com');
        $admin3->setForename('Stuart');
        $admin3->setSurname('Rayson');
        $admin3->setEnabled(true);
        $admin3->setLastActivity(new \DateTime());

        $manager->persist($admin3);
        $this->addReference('admin-stuart', $admin3);

        $developer = new User();
        $developer->setUsername('developer');
        $developer->setPlainPassword('password');
        $developer->setSuperAdmin(false);
        $developer->setEmail('developer@gettickit.com');
        $developer->setForename('Tickit');
        $developer->setSurname('Developer');
        $developer->setEnabled(true);
        $developer->setLastActivity(new \DateTime());

        $manager->persist($developer);
        $this->addReference('developer', $developer);

        //add other users for development environment here

        $faker = Factory::create();

        $i = 30;
        while ($i--) {
            $user = new User();
            $user->setUsername($faker->userName);
            $user->setPlainPassword('password');
            $user->setSuperAdmin(false);
            $user->setEmail($faker->safeEmail);
            $user->setForename($faker->firstName);
            $user->setSurname($faker->lastName);
            $user->setEnabled(true);
            $user->setLastActivity(new \DateTime());
            $user->addRole('ROLE_USER');

            $manager->persist($user);
        }

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
