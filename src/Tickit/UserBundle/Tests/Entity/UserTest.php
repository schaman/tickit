<?php

namespace Tickit\UserBundle\Tests\Entity;

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
     * Tests the setAdmin() method
     *
     * @return void
     */
    public function testSetAdminGrantsAdminRoleWhenPassingTrue()
    {
        $user = new User();
        $user->setAdmin(true);

        $this->assertTrue($user->isAdmin());
    }

    /**
     * Tests the setAdmin() method
     *
     * @return void
     */
    public function testSetAdminRemovesAdminRoleWhenPassingFalse()
    {
        $user = new User();
        $user->setAdmin(true);

        $this->assertTrue($user->isAdmin());
        $user->setAdmin(false);
        $this->assertFalse($user->isAdmin());
    }
}
