<?php

namespace Tickit\UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Tickit\UserBundle\Entity\Group;
use Tickit\UserBundle\Entity\User;

/**
 * Loads default Group related data into the application
 *
 * @author James Halsall <james.t.halsall@googlemail.com>
 */
class LoadGroupData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * Initiates the data loading
     *
     * @param  \Doctrine\Common\Persistence\ObjectManager $manager
     * @return void
     */
    public function load(ObjectManager $manager)
    {
        $adminGroup = new Group('Administrators', array(User::ROLE_SUPER_ADMIN));
        $manager->persist($adminGroup);

        $devGroup = new Group('Developers', array(User::ROLE_DEFAULT));
        $manager->persist($devGroup);

        $clientGroup = new Group('Clients', array(User::ROLE_DEFAULT));
        $manager->persist($clientGroup);

        $testGroup = new Group('Testers', array(User::ROLE_DEFAULT));
        $manager->persist($testGroup);

        $this->addReference('admin-group', $adminGroup);
        $this->addReference('dev-group', $devGroup);
        $this->addReference('client-group', $clientGroup);
        $this->addReference('test-group', $testGroup);

        $manager->flush();
    }

    /**
     * Returns the order number for this set of fixtures
     *
     * @return int
     */
    public function getOrder()
    {
        return 1;
    }

}