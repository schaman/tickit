<?php

namespace Tickit\UserBundle\Tests\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Tickit\UserBundle\Entity\Group;

/**
 * Group entity tests
 *
 * @package Tickit\UserBundle\Tests\Entity
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class GroupTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Tests the setPermissions() method
     *
     * @return void
     */
    public function testSetPermissionsConvertsArrayToCollection()
    {
        $group = new Group('test');
        $group->setPermissions(array(1, 2, 3));

        $this->assertInstanceOf('Doctrine\Common\Collections\ArrayCollection', $group->getPermissions());
    }

    /**
     * Tests the setPermissions() method
     *
     * @return void
     */
    public function testSetPermissionsAcceptsCollection()
    {
        $group = new Group('test');
        $group->setPermissions(new ArrayCollection(array(1, 2, 3)));

        $this->assertInstanceOf('Doctrine\Common\Collections\ArrayCollection', $group->getPermissions());
    }

    /**
     * Tests the clearPermissions() method
     *
     * @return void
     */
    public function testClearPermissionsResetsPermissionsToNull()
    {
        $group = new Group('test');
        $group->setPermissions(array(1, 2, 3));

        $permissions = $group->getPermissions();
        $this->assertEquals(array(1, 2, 3), $permissions->toArray());

        $group->clearPermissions();
        $this->assertNull($group->getPermissions());
    }
}
