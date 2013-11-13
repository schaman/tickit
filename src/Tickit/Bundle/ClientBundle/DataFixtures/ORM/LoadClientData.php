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

namespace Tickit\Bundle\ClientBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Tickit\Component\Model\Client\Client;

/**
 * Loads client data fixtures.
 *
 * @package Tickit\Bundle\ClientBundle\DataFixtures\ORM
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class LoadClientData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager The object mangager
     */
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();

        $i = 50;
        while ($i--) {
            $client = new Client();
            $client->setName($faker->company)
                   ->setUrl($faker->url)
                   ->setNotes($faker->text(300));

            $client->incrementTotalProjects();
            $client->incrementTotalProjects();

            $this->setReference('client-' . $i, $client);

            $manager->persist($client);
        }

        $manager->flush();
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 8;
    }
}
