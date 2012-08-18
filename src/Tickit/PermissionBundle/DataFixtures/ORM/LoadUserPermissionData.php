<?php

namespace Tickit\PreferenceBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Tickit\PermissionBundle\Entity\Permission;

/**
 * Loads default permission records into the database
 *
 * @author James Halsall <james.t.halsall@googlemail.com>
 */
class LoadUserPermissionData extends AbstractFixture implements OrderedFixtureInterface
{

    public function load(ObjectManager $manager)
    {

    }

    /**
     * Gets the order number for this set of fixtures
     *
     * @return int
     */
    public function getOrder()
    {
        return 10;
    }

}
 
