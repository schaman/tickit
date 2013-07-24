<?php

namespace Tickit\TeamBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Tickit\TeamBundle\Entity\Team;

/**
 * Loads team data into the application
 *
 * @package Tickit\TeamBundle\DataFixtures\ORM
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class LoadTeamData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * Loads team data into the application database
     *
     * @param ObjectManager $manager The entity manager
     *
     * @return void
     */
    public function load(ObjectManager $manager)
    {
        $team = new Team();
        $team->setName('Test Team 1');

        $team2 = clone $team;
        $team2->setName('Test Team 2');

        $manager->persist($team);
        $manager->persist($team2);

        $manager->persist($team);
        $manager->flush();
    }

    /**
     * Returns the order number for this set of fixtures
     *
     * @return integer
     */
    public function getOrder()
    {
        return 2;
    }
}
