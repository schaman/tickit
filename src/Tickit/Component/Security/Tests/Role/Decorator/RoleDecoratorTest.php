<?php

namespace Tickit\Component\Security\Tests\Role\Decorator;

use Symfony\Component\Security\Core\Role\Role;
use Symfony\Component\Security\Core\Role\RoleInterface;

/**
 * RoleDecorator tests
 *
 * @package Tickit\Component\Security\Tests\Role\Decorator
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class RoleDecoratorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Tests the decorate() method
     */
    public function testDecorateThrowsExceptionForInvalidRoleName()
    {
        $this->markTestIncomplete();
    }

    /**
     * Tests the decorate() method
     *
     * @dataProvider getRoleFixtures
     */
    public function testDecorateReturnsCorrectValue(RoleInterface $role)
    {
        $this->markTestIncomplete();
    }

    /**
     * Gets Role instances for data fixtures
     */
    public function getRoleFixtures()
    {
        return [
            [new Role('ROLE_USER'), 'Tickit Login'],
            [new Role('ROLE_ADMIN'), 'Tickit Administrator'],
            [new Role('ROLE_SUPER_ADMIN'), 'Tickit Super Administrator'],
            [new Role('ROLE_ALLOWED_TO_SWITCH', 'Tickit Switch User')]
        ];
    }
}
