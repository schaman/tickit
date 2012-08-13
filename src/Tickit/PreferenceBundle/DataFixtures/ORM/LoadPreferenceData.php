<?php

namespace Tickit\PreferenceBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Tickit\PreferenceBundle\Entity\Preference;
use Tickit\PreferenceBundle\Entity;

class LoadPreferenceData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * Loads default preferences into the application database
     *
     * @param  \Doctrine\Common\Persistence\ObjectManager $manager
     * @return void
     */
    public function load(ObjectManager $manager)
    {
        $preference1 = new Preference();
        $preference1->setName('Allow user registrations');
        $preference1->setSystemName('system.registration.allowed');
        $preference1->setDefaultValue('0');
        $preference1->setType(Preference::TYPE_SYSTEM);
        $manager->persist($preference1);

        $preference2 = new Preference();
        $preference2->setName('Require approval upon registration');
        $preference2->setSystemName('system.registration.require_approval');
        $preference2->setDefaultValue('1');
        $preference2->setType(Preference::TYPE_SYSTEM);
        $manager->persist($preference2);

        //add more default preferences here
        $manager->flush();
    }

    /**
     * Returns the order number for this set of fixtures
     *
     * @return int
     */
    public function getOrder()
    {
        return 3;
    }
}
 
