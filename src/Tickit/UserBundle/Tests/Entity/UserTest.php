<?php

namespace Tickit\UserBundle\Tests\Entity;

use Doctrine\Common\Collections\ArrayCollection;
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
        $user = new User();
        $user->setPermissions(array(1, 2, 3));

        $permissions = $user->getPermissions();
        $this->assertEquals(array(1, 2, 3), $permissions->toArray());

        $user->clearPermissions();
        $this->assertNull($user->getPermissions());
    }
}
