<?php

namespace Tickit\PermissionBundle\Tests\Hasher;

use Faker\Factory;
use PHPUnit_Framework_TestCase;
use Tickit\PermissionBundle\Entity\Permission;
use Tickit\PermissionBundle\Hasher\PermissionsHasher;

/**
 * PermissionsHasher tests.
 *
 * @package Tickit\PermissionBundle\Tests\Hasher
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class PermissionsHasherTest extends PHPUnit_Framework_TestCase
{
    /**
     * Tests the hash() method
     *
     * @expectedException \InvalidArgumentException
     *
     * @return void
     */
    public function testHashThrowsExceptionForEmptyPermissionsData()
    {
        $hasher = new PermissionsHasher();
        $hash = $hasher->hash(array());
    }

    /**
     * Tests the hash() method
     *
     * @expectedException \InvalidArgumentException
     *
     * @return void
     */
    public function testHashThrowsExceptionForInvalidPermissionsData()
    {
        $hasher = new PermissionsHasher();
        $hasher->hash(array('not a permission instance'));
    }

    /**
     * Tests the hash() method
     *
     * @return void
     */
    public function testHashReturnsValidHashForValidPermissionData()
    {
        $faker = Factory::create();

        $permission1 = new Permission();
        $permission1->setName($faker->sentence(4))
                    ->setSystemName('system.name.1');

        $permission2 = new Permission();
        $permission2->setName($faker->sentence(3))
                    ->setSystemName('system.name.2');

        $permissionsData = array($permission1, $permission2);

        $hasher = new PermissionsHasher();
        $hash = $hasher->hash($permissionsData);

        $this->assertInternalType('string', $hash);
        $this->assertEquals(40, strlen($hash));
    }
}
