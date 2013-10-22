<?php

namespace Tickit\ClientBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Tickit\ClientBundle\Entity\Client;

/**
 * Loads client data fixtures.
 *
 * @package Tickit\ClientBundle\DataFixtures\ORM
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
