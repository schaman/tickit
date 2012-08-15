<?php

namespace Tickit\UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Tickit\UserBundle\Entity\User;
use DateTime;

/**
 * Loads default users into the application
 */
class LoadUserData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * Initiates the data loading
     *
     * @param  \Doctrine\Common\Persistence\ObjectManager $manager
     * @return void
     */
    public function load(ObjectManager $manager)
    {
        $admin1 = new User();
        $admin1->setUsername('james');
        $admin1->setPlainPassword('password');
        $admin1->setSuperAdmin(true);
        $admin1->setEmail('james.t.halsall@googlemail.com');
        $admin1->setEnabled(true);
        $admin1->setLastActivity(new DateTime());
        $admin1->addRole('ROLE_SUPER_ADMIN');
        $admin1->addGroup($this->getReference('admin-group'));

        $manager->persist($admin1);
        $this->addReference('admin-james', $admin1);

        $admin2 = new User();
        $admin2->setUsername('mark');
        $admin2->setPlainPassword('password');
        $admin2->setSuperAdmin(true);
        $admin2->setEmail('mw870618@gmail.com');
        $admin2->setEnabled(true);
        $admin2->setLastActivity(new DateTime());
        $admin2->addRole('ROLE_SUPER_ADMIN');
        $admin2->addGroup($this->getReference('admin-group'));

        $manager->persist($admin2);
        $this->addReference('admin-mark', $admin2);
        
        $developer = new User();
        $developer->setUsername('developer');
        $developer->setPlainPassword('password');
        $developer->setSuperAdmin(false);
        $developer->setEmail('developer@gettickit.com');
        $developer->setEnabled(true);
        $developer->setLastActivity(new DateTime());
        $developer->addGroup($this->getReference('dev-group'));

        $manager->persist($developer);
        $this->addReference('developer', $developer);

        //add other users for development environment here

        $manager->flush();
    }

    /**
     * Returns the order number for this set of fixtures
     *
     * @return int
     */
    public function getOrder()
    {
        return 2;
    }
}
 
