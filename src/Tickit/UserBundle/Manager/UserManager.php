<?php

namespace Tickit\UserBundle\Manager;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\Common\Persistence\ObjectRepository;
use FOS\UserBundle\Model\UserInterface;
use FOS\UserBundle\Model\UserManagerInterface;
use Tickit\CoreBundle\Event\Dispatcher\AbstractEntityEventDispatcher;
use Tickit\CoreBundle\Manager\AbstractManager;
use Tickit\UserBundle\Entity\Repository\UserRepository;
use Tickit\UserBundle\Entity\User;

/**
 * User entity manager.
 *
 * Provides functionality for managing User objects in the application.
 *
 * Because the user bundle relies on the use of FOSUserBundle we need to use the
 * FOS user manager where possible to ensure correct functionality.
 *
 * @package Tickit\UserBundle\Manager
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class UserManager extends AbstractManager implements UserManagerInterface
{
    /**
     * The FOS user manager
     *
     * @var UserManagerInterface
     */
    protected $fosManager;

    /**
     * Constructor.
     *
     * @param Registry                      $doctrine   The doctrine service
     * @param AbstractEntityEventDispatcher $dispatcher The event dispatcher
     * @param UserManagerInterface          $fosManager The FOS user manager
     */
    public function __construct(Registry $doctrine, AbstractEntityEventDispatcher $dispatcher, UserManagerInterface $fosManager)
    {
        parent::__construct($doctrine, $dispatcher);
        $this->fosManager = $fosManager;
    }

    /**
     * {@inheritDoc}
     *
     * @return ObjectRepository
     */
    public function getRepository()
    {
        $repository = $this->em->getRepository('TickitUserBundle:User');

        return $repository;
    }

    /**
     * {@inheritDoc}
     *
     * @param object  $entity The user object to create in the entity manager
     * @param boolean $flush  True to flush changes in the entity manager
     *
     * @return User
     */
    public function create($entity, $flush = true)
    {
        /** @var User $entity */
        $roles = $entity->getRoles();
        if (empty($roles)) {
            $entity->addRole(User::ROLE_DEFAULT);
        }

        $this->fosManager->updateCanonicalFields($entity);
        $this->fosManager->updatePassword($entity);

        return parent::create($entity, $flush);
    }

    /**
     * {@inheritDoc}
     *
     * @param object  $entity The user object to update in the entity manager
     * @param boolean $flush  True to flush changes in the entity manager
     *
     * @return User
     */
    public function update($entity, $flush = true)
    {
        $this->fosManager->updateCanonicalFields($entity);
        $this->fosManager->updatePassword($entity);

        return parent::create($entity, $flush);
    }

    /**
     * {@inheritDoc}
     *
     * @param object $entity The entity to delete
     */
    public function delete($entity)
    {
        $this->fosManager->deleteUser($entity);
    }

    /**
     * {@inheritDoc}
     *
     * @param object $entity The entity in its current state
     *
     * @return User
     */
    protected function fetchEntityInOriginalState($entity)
    {
        $user = $this->em->find('\Tickit\UserBundle\Entity\User', $entity->getId());

        return $user;
    }

    /**
     * Creates an empty user instance.
     *
     * @return UserInterface
     */
    public function createUser()
    {
        return new User();
    }

    /**
     * Deletes a user.
     *
     * @param UserInterface $user
     *
     * @return void
     */
    public function deleteUser(UserInterface $user)
    {
        $this->delete($user);
    }

    /**
     * Finds one user by the given criteria.
     *
     * @param array $criteria
     *
     * @return UserInterface
     */
    public function findUserBy(array $criteria)
    {
        return $this->getRepository()->findOneBy($criteria);
    }

    /**
     * Find a user by its username.
     *
     * @param string $username
     *
     * @return UserInterface or null if user does not exist
     */
    public function findUserByUsername($username)
    {
        return $this->getRepository()->findByUsernameOrEmail($username, UserRepository::COLUMN_USERNAME);
    }

    /**
     * Finds a user by its email.
     *
     * @param string $email
     *
     * @return UserInterface or null if user does not exist
     */
    public function findUserByEmail($email)
    {
        return $this->getRepository()->findByUsernameOrEmail($email, UserRepository::COLUMN_EMAIL);
    }

    /**
     * Finds a user by its username or email.
     *
     * @param string $usernameOrEmail
     *
     * @return UserInterface or null if user does not exist
     */
    public function findUserByUsernameOrEmail($usernameOrEmail)
    {
        if (filter_var($usernameOrEmail, FILTER_VALIDATE_EMAIL)) {
            return $this->findUserByEmail($usernameOrEmail);
        }

        return $this->findUserByUsername($usernameOrEmail);
    }

    /**
     * Finds a user by its confirmationToken.
     *
     * @param string $token
     *
     * @return UserInterface or null if user does not exist
     */
    public function findUserByConfirmationToken($token)
    {
        return $this->findUserByConfirmationToken($token);
    }

    /**
     * Returns a collection with all user instances.
     *
     * @return \Traversable
     */
    public function findUsers()
    {
        return $this->getRepository()->findAll();
    }

    /**
     * Returns the user's fully qualified class name.
     *
     * @return string
     */
    public function getClass()
    {
        return 'Tickit\UserBundle\Entity\User';
    }

    /**
     * Reloads a user.
     *
     * @param UserInterface $user
     *
     * @return void
     */
    public function reloadUser(UserInterface $user)
    {
        $this->em->refresh($user);
    }

    /**
     * Updates a user.
     *
     * @param UserInterface $user
     *
     * @return void
     */
    public function updateUser(UserInterface $user)
    {
        $this->update($user);
    }

    /**
     * Updates the canonical username and email fields for a user.
     *
     * @param UserInterface $user
     *
     * @return void
     */
    public function updateCanonicalFields(UserInterface $user)
    {
        $this->fosManager->updateCanonicalFields($user);
    }

    /**
     * Updates a user password if a plain password is set.
     *
     * @param UserInterface $user
     *
     * @return void
     */
    public function updatePassword(UserInterface $user)
    {
        $this->fosManager->updatePassword($user);
    }
}
