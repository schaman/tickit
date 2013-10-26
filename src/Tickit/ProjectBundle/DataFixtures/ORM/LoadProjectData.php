<?php

namespace Tickit\ProjectBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
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
        $faker = Factory::create();

        $i = 50;
        while ($i--) {
            $project = new Project();
            $project->setName($faker->sentence(3))
                    ->setClient($this->getReference('client-' . $i));

            $project2 = new Project();
            $project2->setName($faker->sentence(3))
                    ->setClient($this->getReference('client-' . $i));

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
