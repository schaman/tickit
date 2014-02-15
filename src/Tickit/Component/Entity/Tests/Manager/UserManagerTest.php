<?php

/*
 * Tickit, an open source web based bug management tool.
 *
 * Copyright (C) 2014  Tickit Project <http://tickit.io>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Tickit\Component\Entity\Tests\Manager;

use Doctrine\ORM\NoResultException;
use Tickit\Component\Entity\Event\EntityEvent;
use Tickit\Component\Entity\Repository\UserRepositoryInterface;
use Tickit\Component\Test\AbstractUnitTest;
use Tickit\Component\Model\User\User;
use Tickit\Component\Entity\Manager\UserManager;

/**
 * UserManager tests
 *
 * @package Tickit\Component\Entity\Tests\Manager
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class UserManagerTest extends AbstractUnitTest
{
    /**
     * The FOS UserManager
     *
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $fosManager;

    /**
     * The user repository
     *
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $userRepo;

    /**
     * Entity manager
     *
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $em;

    /**
     * Event dispatcher
     *
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $dispatcher;

    /**
     * Setup
     */
    protected function setUp()
    {
        $this->fosManager = $this->getMock('\FOS\UserBundle\Model\UserManagerInterface');
        $this->userRepo = $this->getMock('\Tickit\Component\Entity\Repository\UserRepositoryInterface');

        $this->em = $this->getMockEntityManager();

        $this->dispatcher = $this->getMockBuilder('\Tickit\Component\Event\User\Dispatcher\UserEventDispatcher')
                                 ->disableOriginalConstructor()
                                 ->getMock();
    }

    /**
     * Tests the getRepository() method
     */
    public function testGetRepositoryReturnsCorrectInstance()
    {
        $this->assertSame($this->userRepo, $this->getUserManager()->getRepository());
    }

    /**
     * Tests the getClass() method
     */
    public function testGetClassReturnsCorrectValue()
    {
        $this->assertEquals('Tickit\Component\Model\User\User', $this->getUserManager()->getClass());
    }

    /**
     * Tests the create() method
     */
    public function testCreatePersistsUserWithDefinedRole()
    {
        $user = new User();
        $user->setRoles(array(User::ROLE_ADMIN));

        $this->fosManager->expects($this->once())
                         ->method('updateCanonicalFields')
                         ->with($user);

        $this->fosManager->expects($this->once())
                         ->method('updatePassword')
                         ->with($user);

        $this->dispatcher->expects($this->once())
                         ->method('dispatchBeforeCreateEvent')
                         ->with($user)
                         ->will($this->returnValue(new EntityEvent($user)));

        $this->em->expects($this->once())
                 ->method('persist')
                 ->with($user);

        $this->em->expects($this->once())
                 ->method('flush');

        $this->dispatcher->expects($this->once())
                         ->method('dispatchCreateEvent')
                         ->with($user);

        $persistedUser = $this->getUserManager()->create($user);
        $this->assertEquals($user, $persistedUser);
        $this->assertEquals($user->getRoles(), $persistedUser->getRoles());
    }

    /**
     * Tests the create() method
     */
    public function testCreatePersistsUserWithoutRole()
    {
        $user = new User();

        $this->fosManager->expects($this->once())
                         ->method('updateCanonicalFields')
                         ->with($user);

        $this->fosManager->expects($this->once())
                         ->method('updatePassword')
                         ->with($user);

        $this->dispatcher->expects($this->once())
                         ->method('dispatchBeforeCreateEvent')
                         ->with($user)
                         ->will($this->returnValue(new EntityEvent($user)));

        $this->em->expects($this->once())
                 ->method('persist')
                 ->with($user);

        $this->em->expects($this->once())
                 ->method('flush');

        $this->dispatcher->expects($this->once())
                         ->method('dispatchCreateEvent')
                         ->with($user);

        $persistedUser = $this->getUserManager()->create($user);
        $this->assertEquals($user, $persistedUser);

        $expectedRoles = array(User::ROLE_DEFAULT);
        $this->assertEquals($expectedRoles, $persistedUser->getRoles());
    }

    /**
     * Tests the updateUser() method
     */
    public function testUpdateUserUpdatesUser()
    {
        $user = new User();
        $user->setId(1)
             ->setForename('updated-name');

        $originalUser = new User();
        $originalUser->setId(1)
                     ->setForename('original-name');

        $this->fosManager->expects($this->once())
                         ->method('updateCanonicalFields')
                         ->with($user);

        $this->fosManager->expects($this->once())
                         ->method('updatePassword')
                         ->with($user);

        $this->userRepo->expects($this->once())
                       ->method('find')
                       ->with(1)
                       ->will($this->returnValue($originalUser));

        $this->dispatcher->expects($this->once())
                         ->method('dispatchBeforeUpdateEvent')
                         ->with($user)
                         ->will($this->returnValue(new EntityEvent($user)));

        $this->em->expects($this->once())
                 ->method('flush');

        $this->dispatcher->expects($this->once())
                         ->method('dispatchUpdateEvent')
                         ->with($user, $originalUser);

        $this->getUserManager()->updateUser($user);
    }

    /**
     * Tests the reloadUser() method
     */
    public function testReloadUserRefreshesUser()
    {
        $user = new User();

        $this->em->expects($this->once())
                 ->method('refresh')
                 ->with($user);

        $this->getUserManager()->reloadUser($user);
    }

    /**
     * Tests the findUserBy() method
     *
     * @expectedException \PHPUnit_Framework_Error_Deprecated
     */
    public function testFindUserByThrowsDeprecatedException()
    {
        $criteria = array('column' => 'value', 'column2' => 'value2');

        $this->getUserManager()->findUserBy($criteria);
    }

    /**
     * Tests the findUserByUsername() method
     */
    public function testFindUserByUsernameFindsUser()
    {
        $username = 'username';
        $user = new User();

        $this->userRepo->expects($this->once())
                       ->method('findByUsernameOrEmail')
                       ->with($username, UserRepositoryInterface::COLUMN_USERNAME)
                       ->will($this->returnValue($user));

        $foundUser = $this->getUserManager()->findUserByUsername($username);
        $this->assertEquals($user, $foundUser);
    }

    /**
     * Tests the findUserByEmail() method
     */
    public function testFindUserByEmailFindsUser()
    {
        $email = 'mail@domain.com';
        $user = new User();

        $this->userRepo->expects($this->once())
                       ->method('findByUsernameOrEmail')
                       ->with($email, UserRepositoryInterface::COLUMN_EMAIL)
                       ->will($this->returnValue($user));

        $foundUser = $this->getUserManager()->findUserByEmail($email);
        $this->assertEquals($user, $foundUser);
    }

    /**
     * Tests the findUserByUsernameOrEmail() method
     */
    public function testFindByUsernameOrEmailFindsUserByEmailAddress()
    {
        $email = 'mail@domain.com';
        $user = new User();

        $this->userRepo->expects($this->once())
                       ->method('findByUsernameOrEmail')
                       ->with($email, UserRepositoryInterface::COLUMN_EMAIL)
                       ->will($this->returnValue($user));

        $foundUser = $this->getUserManager()->findUserByUsernameOrEmail($email);
        $this->assertEquals($user, $foundUser);
    }

    /**
     * Tests the findUserByUsernameOrEmail() method
     */
    public function testFindByUsernameOrEmailFindsUserByUsername()
    {
        $username = 'username';
        $user = new User();

        $this->userRepo->expects($this->once())
                       ->method('findByUsernameOrEmail')
                       ->with($username, UserRepositoryInterface::COLUMN_USERNAME)
                       ->will($this->returnValue($user));

        $foundUser = $this->getUserManager()->findUserByUsernameOrEmail($username);
        $this->assertEquals($user, $foundUser);
    }

    /**
     * Tests the findUserByConfirmationToken() method
     */
    public function testFindUserByConfirmationTokenFindsUser()
    {
        $token = 'token-value';
        $user = new User();

        $this->userRepo->expects($this->once())
                       ->method('findByConfirmationToken')
                       ->with($token)
                       ->will($this->returnValue($user));

        $foundUser = $this->getUserManager()->findUserByConfirmationToken($token);
        $this->assertEquals($user, $foundUser);
    }

    /**
     * Tests the findUsers() method
     *
     * @expectedException \PHPUnit_Framework_Error_Deprecated
     */
    public function testFindUsersThrowsException()
    {
        $this->getUserManager()->findUsers();
    }

    /**
     * Tests the updateCanonicalFields() method
     */
    public function testUpdateCanonicalFieldsCallsFosManager()
    {
        $user = new User();

        $this->fosManager->expects($this->once())
                         ->method('updateCanonicalFields')
                         ->with($user);

        $this->getUserManager()->updateCanonicalFields($user);
    }

    /**
     * Tests the updatePassword() method
     */
    public function testUpdatePasswordCallsFosManager()
    {
        $user = new User();

        $this->fosManager->expects($this->once())
                         ->method('updatePassword')
                         ->with($user);

        $this->getUserManager()->updatePassword($user);
    }

    /**
     * Tests the find() method
     */
    public function testFindReturnsNullWhenNoResultExceptionThrownByRepository()
    {
        $this->userRepo->expects($this->once())
                       ->method('find')
                       ->with(1)
                       ->will($this->throwException(new NoResultException()));

        $returnValue = $this->getUserManager()->find(1);
        $this->assertNull($returnValue);
    }

    /**
     * Tests the createUser() method
     */
    public function testCreateUserReturnsCorrectInstance()
    {
        $this->assertInstanceOf('\Tickit\Component\Model\User\User', $this->getUserManager()->createUser());
    }

    /**
     * Gets an instance of UserManager
     *
     * @return UserManager
     */
    protected function getUserManager()
    {
        return new UserManager($this->userRepo, $this->em, $this->dispatcher, $this->fosManager);
    }
}
