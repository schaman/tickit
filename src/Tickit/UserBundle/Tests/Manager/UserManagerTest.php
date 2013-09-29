<?php

namespace Tickit\UserBundle\Tests\Manager;

use Doctrine\ORM\NoResultException;
use Tickit\CoreBundle\Tests\AbstractUnitTest;
use Tickit\UserBundle\Entity\Repository\UserRepository;
use Tickit\UserBundle\Entity\User;
use Tickit\UserBundle\Event\BeforeCreateEvent;
use Tickit\UserBundle\Event\BeforeUpdateEvent;
use Tickit\UserBundle\Manager\UserManager;

/**
 * UserManager tests
 *
 * @package Tickit\UserBundle\Tests\Manager
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
        $this->fosManager = $this->getMockBuilder('\FOS\UserBundle\Model\UserManagerInterface')
                                 ->disableOriginalConstructor()
                                 ->getMock();

        $this->userRepo = $this->getMockBuilder('\Tickit\UserBundle\Entity\Repository\UserRepository')
                               ->disableOriginalConstructor()
                               ->getMock();

        $this->em = $this->getMockEntityManager();

        $this->dispatcher = $this->getMockBuilder('\Tickit\UserBundle\Event\Dispatcher\UserEventDispatcher')
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
        $this->assertEquals('Tickit\UserBundle\Entity\User', $this->getUserManager()->getClass());
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
                         ->will($this->returnValue(new BeforeCreateEvent($user)));

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
                         ->will($this->returnValue(new BeforeCreateEvent($user)));

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
                         ->will($this->returnValue(new BeforeUpdateEvent($user)));

        $this->em->expects($this->once())
                 ->method('persist')
                 ->with($user);

        $this->em->expects($this->once())
                 ->method('flush');

        $this->dispatcher->expects($this->once())
                         ->method('dispatchUpdateEvent')
                         ->with($user, $originalUser);

        $this->getUserManager()->updateUser($user);
    }

    /**
     * Tests the deleteUser() method
     */
    public function testDeleteUserRemovesUsers()
    {
        $user = new User();

        $this->fosManager->expects($this->once())
                         ->method('deleteUser')
                         ->with($user);

        $this->getUserManager()->deleteUser($user);
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
     */
    public function testFindUserByFindsUserWithCriteria()
    {
        $criteria = array('column' => 'value', 'column2' => 'value2');

        $users = array(new User(), new User());

        $this->userRepo->expects($this->once())
                       ->method('findOneBy')
                       ->with($criteria)
                       ->will($this->returnValue($users));

        $foundUsers = $this->getUserManager()->findUserBy($criteria);
        $this->assertEquals($users, $foundUsers);
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
                       ->with($username, UserRepository::COLUMN_USERNAME)
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
                       ->with($email, UserRepository::COLUMN_EMAIL)
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
                       ->with($email, UserRepository::COLUMN_EMAIL)
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
                       ->with($username, UserRepository::COLUMN_USERNAME)
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

        $this->fosManager->expects($this->once())
                         ->method('findUserByConfirmationToken')
                         ->with($token)
                         ->will($this->returnValue($user));

        $foundUser = $this->getUserManager()->findUserByConfirmationToken($token);
        $this->assertEquals($user, $foundUser);
    }

    /**
     * Tests the findUsers() method
     */
    public function testFindUsersFindsAllUsers()
    {
        $users = array(new User(), new User(), new User());

        $this->userRepo->expects($this->once())
             ->method('findAll')
             ->will($this->returnValue($users));

        $foundUsers = $this->getUserManager()->findUsers();
        $this->assertEquals($users, $foundUsers);
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
                       ->method('findById')
                       ->with(1)
                       ->will($this->throwException(new NoResultException()));

        $returnValue = $this->getUserManager()->find(1);
        $this->assertNull($returnValue);
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
