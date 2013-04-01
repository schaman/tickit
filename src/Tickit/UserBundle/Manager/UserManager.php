<?php

namespace Tickit\UserBundle\Manager;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\Common\Persistence\ObjectRepository;
use FOS\UserBundle\Model\UserManagerInterface;
use Tickit\CoreBundle\Event\Dispatcher\AbstractEntityEventDispatcher;
use Tickit\CoreBundle\Manager\AbstractManager;
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
class UserManager extends AbstractManager
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
}
