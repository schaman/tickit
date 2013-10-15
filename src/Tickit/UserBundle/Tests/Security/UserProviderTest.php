<?php

namespace Tickit\UserBundle\Tests\Security;

use Tickit\UserBundle\Entity\User;
use Tickit\UserBundle\Security\UserProvider;
use Tickit\UserBundle\Tests\Security\Mock\MockInvalidTypeUser;

/**
 * UserProvider tests
 *
 * @package Tickit\UserBundle\Tests\Security
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class UserProviderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $userManager;

    /**
     * Setup
     */
    protected function setUp()
    {
        $this->userManager = $this->getMockBuilder('\Tickit\UserBundle\Manager\UserManager')
                                  ->disableOriginalConstructor()
                                  ->getMock();
    }

    /**
     * Tests the supportsClass() method
     *
     * @return void
     */
    public function testSupportsClassReturnsTrueForValidClass()
    {
        $this->userManager->expects($this->once())
                          ->method('getClass')
                          ->will($this->returnValue('Tickit\UserBundle\Entity\User'));

        $this->assertTrue($this->getProvider()->supportsClass('Tickit\UserBundle\Entity\User'));
    }

    /**
     * Tests the supportsClass() method
     *
     * @return void
     */
    public function testSupportsClassReturnsFalseForInvalidClass()
    {
        $this->userManager->expects($this->once())
                          ->method('getClass')
                          ->will($this->returnValue('Tickit\UserBundle\Entity\User'));

        $this->assertFalse($this->getProvider()->supportsClass('\stdClass'));
    }

    /**
     * Tests the loadByUsername() method
     *
     * @expectedException \Symfony\Component\Security\Core\Exception\UsernameNotFoundException
     */
    public function testLoadByUsernameThrowsExceptionForNonExistentUser()
    {
        $user = new User();
        $user->setUsername('username');

        $this->userManager->expects($this->once())
                          ->method('findUserByUsernameOrEmail')
                          ->with($user->getUsername())
                          ->will($this->returnValue(null));

        $this->getProvider()->loadUserByUsername($user->getUsername());
    }

    /**
     * Tests the loadByUsername() method
     */
    public function testLoadByUsernameReturnsExceptedUser()
    {
        $user = new User();
        $user->setUsername('username');

        $this->userManager->expects($this->once())
                          ->method('findUserByUsernameOrEmail')
                          ->with($user->getUsername())
                          ->will($this->returnValue($user));

        $this->assertSame($user, $this->getProvider()->loadUserByUsername($user->getUsername()));
    }

    /**
     * Tests the refreshUser() method
     *
     * @expectedException \Symfony\Component\Security\Core\Exception\UnsupportedUserException
     */
    public function testRefreshUserThrowsExceptionForInvalidUserInstance()
    {
        $this->getProvider()->refreshUser(new MockInvalidTypeUser());
    }

    /**
     * Tests the refreshUser() method
     *
     * @expectedException \Symfony\Component\Security\Core\Exception\UsernameNotFoundException
     */
    public function testRefreshUserThrowsExceptionForNonExistentUser()
    {
        $user = new User();

        $provider = $this->getProvider();
        $provider->refreshUser($user);
    }

    /**
     * Tests the refreshUser() method
     */
    public function testRefreshUserReturnsReloadedUser()
    {
        $user = new User();
        $user->setId(1);
        $reloadedUser = new User();

        $this->userManager->expects($this->once())
                          ->method('findUserBy')
                          ->with(['id' => $user->getId()])
                          ->will($this->returnValue($reloadedUser));

        $this->assertSame($reloadedUser, $this->getProvider()->refreshUser($user));
    }

    /**
     * Gets a user provider
     *
     * @return UserProvider
     */
    private function getProvider()
    {
        return new UserProvider($this->userManager);
    }
}
