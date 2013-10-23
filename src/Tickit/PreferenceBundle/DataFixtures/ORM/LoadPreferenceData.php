<?php

/*
 * Tickit, an source web based bug management tool.
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

namespace Tickit\PreferenceBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Tickit\PreferenceBundle\Entity\Preference;

/**
 * Loads default preferences into the application
 *
 * @package Tickit\PreferenceBundle\DataFixtures\ORM
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class LoadPreferenceData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * Initiates the loading of data
     *
     * @param ObjectManager $manager The object manager
     *
     * @return void
     */
    public function load(ObjectManager $manager)
    {
        $this->loadSystemPreferences($manager);
        $this->loadUserPreferences($manager);
    }

    /**
     * Loads user related preferences into the application database
     *
     * @param ObjectManager $manager
     *
     * @return void
     */
    protected function loadUserPreferences(ObjectManager $manager)
    {
        $userPreference1 = new Preference();
        $userPreference1->setName('Notifications for ticket status changes');
        $userPreference1->setSystemName('user.notifications.ticket.status_change');
        $userPreference1->setDefaultValue('1');
        $userPreference1->setType(Preference::TYPE_USER);
        $manager->persist($userPreference1);

        $userPreference2 = new Preference();
        $userPreference2->setName('Notifications for new ticket comments');
        $userPreference2->setSystemName('user.notifications.ticket.new_comment');
        $userPreference2->setDefaultValue('1');
        $userPreference2->setType(Preference::TYPE_USER);
        $manager->persist($userPreference2);

        $userPreference3 = new Preference();
        $userPreference3->setName('Notifications for new tickets');
        $userPreference3->setSystemName('user.notifications.ticket.new_tickets');
        $userPreference3->setDefaultValue('1');
        $userPreference3->setType(Preference::TYPE_USER);
        $manager->persist($userPreference3);

        //add more default preferences here
        $manager->flush();
    }

    /**
     * Loads system related preferences into the application database
     *
     * @param ObjectManager $manager The object manager
     *
     * @return void
     */
    protected function loadSystemPreferences(ObjectManager $manager)
    {
        $systemPreference1 = new Preference();
        $systemPreference1->setName('Allow user registrations');
        $systemPreference1->setSystemName('system.registration.allowed');
        $systemPreference1->setDefaultValue('0');
        $systemPreference1->setType(Preference::TYPE_SYSTEM);
        $manager->persist($systemPreference1);

        $systemPreference2 = new Preference();
        $systemPreference2->setName('Require approval upon registration');
        $systemPreference2->setSystemName('system.registration.require_approval');
        $systemPreference2->setDefaultValue('1');
        $systemPreference2->setType(Preference::TYPE_SYSTEM);
        $manager->persist($systemPreference2);

        $manager->flush();
    }

    /**
     * Returns the order number for this set of fixtures
     *
     * @return int
     */
    public function getOrder()
    {
        return 5;
    }
}
