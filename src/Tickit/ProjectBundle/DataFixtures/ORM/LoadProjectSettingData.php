<?php

/*
 * 
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
 * 
 */

namespace Tickit\ProjectBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Tickit\ProjectBundle\Entity\ProjectSetting;

/**
 * Loads project setting data into the application
 *
 * @author James Halsall <james.t.halsall@googlemail.com>
 */
class LoadProjectSettingData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * Loads default project settings into the application database
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $setting1 = new ProjectSetting();
        $setting1->setName('Maximum hours per week');
        $setting1->setDefaultValue('0');
        $manager->persist($setting1);

        //add more project settings here

        $manager->flush();
    }

    /**
     * Returns the order number for this set of fixtures
     *
     * @return int
     */
    public function getOrder()
    {
        return 10;
    }
}
