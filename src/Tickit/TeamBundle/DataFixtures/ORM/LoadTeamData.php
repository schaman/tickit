<?php

namespace Tickit\TeamBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Tickit\TeamBundle\Entity\Team;

/**
 * Loads default team data into the application
 *
 * @package Tickit\TeamBundle\DataFixtures\ORM
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class LoadTeamData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * Initiates the loading of data
     *
     * @param \Doctrine\Common\Persistence\ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $team = new Team();
        $team->setName('Test Development Team');
    }

    /**
     * Returns the order number for the data fixtures
     *
     * @return int
     */
    public function getOrder()
    {
        return 2;
    }
}
