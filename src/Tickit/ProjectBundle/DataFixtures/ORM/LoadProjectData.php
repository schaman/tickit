<?php

namespace Tickit\ProjectBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Tickit\ProjectBundle\Entity\Project;

/**
 * Loads project data into the application
 *
 * @package Tickit\ProjectBundle\DataFixtures\ORM
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
        $project = new Project();
        $project->setName('Test Project 1');

        $project2 = clone $project;
        $project2->setName('Test Project 2');

        $manager->persist($project);
        $manager->persist($project2);

        $manager->flush();

        $this->setReference('project-1', $project);
        $this->setReference('project-2', $project2);
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
