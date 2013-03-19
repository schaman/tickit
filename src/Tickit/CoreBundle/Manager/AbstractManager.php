<?php

namespace Tickit\CoreBundle\Manager;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\EventDispatcher\ContainerAwareEventDispatcher;
use Tickit\CoreBundle\Entity\Interfaces\DeletableEntityInterface;

/**
 * Abstract entity manager
 *
 * Responsible for providing base functionality for all entity managers in
 * the application
 *
 * @package Tickit\CoreBundle\Manager
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
abstract class AbstractManager
{
    /**
     * Entity manager
     *
     * @var ObjectManager
     */
    protected $em;

    /**
     * The event dispatcher
     *
     * @var ContainerAwareEventDispatcher
     */
    protected $dispatcher;

    /**
     * Constructor.
     *
     * @param Registry                      $doctrine   The doctrine registry service
     * @param ContainerAwareEventDispatcher $dispatcher The event dispatcher
     */
    public function __construct(Registry $doctrine, ContainerAwareEventDispatcher $dispatcher)
    {
        $this->em = $doctrine->getManager();
        $this->dispatcher = $dispatcher;
    }

    /**
     * Persists an entity in the entity manager.
     *
     * This method provides base functionality for persisting, any event dispatching
     * should be handled by the implementing class
     *
     * @param object  $entity The entity to persist
     * @param boolean $flush  True to automatically flush changes to the database, false otherwise (defaults to true)
     *
     * @return void
     */
    public function persist($entity, $flush = true)
    {
        $this->em->persist($entity);

        if (false !== $flush) {
            $this->em->flush();
        }
    }

    /**
     * Deletes an entity from the entity manager.
     *
     * This method provides base functionality for deletion, any event dispatching
     * should be handled by the implementing class.
     *
     * @param DeletableEntityInterface $entity The entity to be deleted
     *
     * @return void
     */
    public function delete(DeletableEntityInterface $entity, $flush = true)
    {
        $entity->delete();
        $this->em->persist($entity);

        if (false !== $flush) {
            $this->em->flush();
        }
    }
}