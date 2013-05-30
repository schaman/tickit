<?php

namespace Tickit\UserBundle\Tests\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Tickit\UserBundle\Entity\Group;
use Tickit\UserBundle\Entity\User;

/**
 * User entity tests.
 *
 * @package Tickit\UserBundle\Tests\Entity
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class UserTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Tests the getFullName() method
     *
     * @return void
     */
    public function testGetFullNameReturnsCorrectValue()
    {
        $user = new User();
        $user->setForename('forename')
             ->setSurname('surname');

        $this->assertEquals('forename surname', $user->getFullName());
    }

    /**
     * Tests the addGroup() method
     *
     * @expectedException \RuntimeException
     *
     * @return void
     */
    public function testAddGroupThrowsExceptionWhenUserAlreadyHasAGroup()
    {
        $user = new User();
        $group = new Group('test');

        $user->addGroup($group);
        $this->assertEquals('test', $user->getGroupName());

        $anotherGroup = new Group('test 2');
        $user->addGroup($anotherGroup);
    }

    /**
     * Tests the setPermissions() method
     *
     * @return void
     */
    public function testSetPermissionsConvertsArrayToCollection()
    {
        $user = new User();
        $user->setPermissions(array(1, 2, 3));

        $this->assertInstanceOf('Doctrine\Common\Collections\ArrayCollection', $user->getPermissions());
    }

    /**
     * Tests the setPermissions() method
     *
     * @return void
     */
    public function testSetPermissionsAcceptsCollection()
    {
        $user = new User();
        $user->setPermissions(new ArrayCollection(array(1, 2, 3)));

        $this->assertInstanceOf('Doctrine\Common\Collections\ArrayCollection', $user->getPermissions());
    }

    /**
     * Tests the clearPermissions() method
     *
     * @return void
     */
    public function testClearPermissionsResetsPermissionsToNull()
    {
        $group = new User();
        $group->setPermissions(array(1, 2, 3));

        $permissions = $group->getPermissions();
        $this->assertEquals(array(1, 2, 3), $permissions->toArray());

        $group->clearPermissions();
        $this->assertNull($group->getPermissions());
    }
}
