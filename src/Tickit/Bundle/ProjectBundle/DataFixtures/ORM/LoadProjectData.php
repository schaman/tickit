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

namespace Tickit\Bundle\ProjectBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Tickit\Component\Model\Project\Project;

/**
 * Loads project data into the application
 *
 * @package Tickit\Bundle\ProjectBundle\DataFixtures\ORM
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class LoadProjectData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * Loads project data into the application database
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();

        $i = 50;
        while ($i--) {
            $project = new Project();
            $project->setName($faker->sentence(3))
                    ->setClient($this->getReference('client-' . $i))
                    ->setIssuePrefix($faker->text(5));

            $project2 = new Project();
            $project2->setName($faker->sentence(3))
                    ->setClient($this->getReference('client-' . $i))
                    ->setIssuePrefix($faker->text(5));

            if (false === $faker->boolean(80)) {
                $project->setStatus(Project::STATUS_ARCHIVED);
            }

            $manager->persist($project);
            $manager->persist($project2);

            $this->setReference('project-' . (($i * 2) + 1), $project);
            $this->setReference('project-' . ($i * 2), $project2);
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
        return 11;
    }
}
