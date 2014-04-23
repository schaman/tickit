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

namespace Tickit\Bundle\IssueBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Tickit\Component\Issue\Listener\IssueNumberListener;
use Tickit\Component\Model\Issue\Issue;
use Tickit\Component\Model\Project\Project;

/**
 * Loads Issue object data
 *
 * @package Tickit\Bundle\IssueBundle\DataFixtures\ORM
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class LoadIssueData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();
        $i = 50;
        $priorities = Issue::getValidPriorities();

        while ($i--) {
            /** @var Project $project */
            $project = $this->getReference('project-' . $i);
            foreach (LoadIssueTypeData::getTypes() as $typeName) {
                $issue = new Issue();
                $issueType = $this->getReference('issue-type-' . strtolower(str_replace(' ', '-', $typeName)));
                $issueStatus = $this->getReference('issue-status-open');
                $issue->setType($issueType)
                      ->setNumber(IssueNumberListener::DEFAULT_ISSUE_NUMBER + $i)
                      ->setDescription(implode(' ', $faker->paragraphs(2)))
                      ->setTitle(implode(' ', $faker->words(8)))
                      ->setStatus($issueStatus)
                      ->setCreatedBy($this->getReference('user-' . $i))
                      ->setAssignedTo($this->getReference('developer'))
                      ->setProject($project)
                      ->setEstimatedHours($faker->randomDigit)
                      ->setActualHours($faker->randomDigit)
                      ->setPriority($priorities[mt_rand(0, count($priorities) - 1)])
                ;

                $manager->persist($issue);
            }

            $manager->flush();
        }
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 20;
    }
}
