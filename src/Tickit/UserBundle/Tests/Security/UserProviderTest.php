<?php

namespace Tickit\UserBundle\Tests\Security;

use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Tickit\CoreBundle\Tests\AbstractFunctionalTest;
use Tickit\UserBundle\Security\UserProvider;
use Tickit\UserBundle\Tests\Security\Mock\MockInvalidTypeUser;

/**
 * UserProvider tests
 *
 * @package Tickit\UserBundle\Tests\Security
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class UserProviderTest extends AbstractFunctionalTest
{
    /**
     * Tests the supportsClass() method
     *
     * @return void
     */
    public function testSupportsClassReturnsTrueForValidClass()
    {
        $provider = $this->getProvider();

        $exists = $provider->supportsClass('Tickit\UserBundle\Entity\User');
        $this->assertTrue($exists);
    }

    /**
     * Tests the supportsClass() method
     *
     * @return void
     */
    public function testSupportsClassReturnsFalseForInvalidClass()
    {
        $provider = $this->getProvider();
        $exists = $provider->supportsClass('\stdClass');
        $this->assertFalse($exists);
    }

    /**
     * Tests the refreshUser() method
     *
     * @expectedException \Symfony\Component\Security\Core\Exception\UnsupportedUserException
     *
     * @return void
     */
    public function testRefreshUserThrowsExceptionForInvalidUserInstance()
    {
        $provider = $this->getProvider();
        $provider->refreshUser(new MockInvalidTypeUser());
    }

    /**
     * Gets a user provider
     *
     * @return UserProvider
     */
    private function getProvider()
    {
        return $this->createClient()->getContainer()->get('tickit_user.user_provider');
    }
}
