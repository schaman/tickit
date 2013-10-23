<?php

namespace Tickit\UserBundle\Tests\Form\Password;

use Tickit\UserBundle\Entity\User;
use Tickit\UserBundle\Form\Password\UserPasswordUpdater;

/**
 * UserPasswordUpdater tests
 *
 * @package Tickit\UserBundle\Tests\Form\Password
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class UserPasswordUpdaterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var UserPasswordUpdater
     */
    private $passwordUpdater;

    /**
     * Setup
     */
    protected function setUp()
    {
        $this->passwordUpdater = new UserPasswordUpdater();
    }

    /**
     * Tests the updatePassword() method
     */
    public function testUpdatePasswordKeepsOriginalPasswordWhenUpdatedUserHasNullPassword()
    {
        $newUser = new User();
        $newUser->setPassword(null);

        $originalUser = new User();
        $originalUser->setPassword('encoded-password');

        $newUser = $this->passwordUpdater->updatePassword($originalUser, $newUser);

        $this->assertEquals('encoded-password', $newUser->getPassword());
    }

    /**
     * Tests the updatePassword() method
     */
    public function testUpdatePasswordSetsNewPassword()
    {
        $newUser = new User();
        $newUser->setPassword('encoded-password');

        $originalUser = new User();

        $newUser = $this->passwordUpdater->updatePassword($originalUser, $newUser);

        $this->assertEquals('encoded-password', $newUser->getPlainPassword());
    }
}
