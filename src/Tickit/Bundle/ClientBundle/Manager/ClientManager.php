<?php

/*
 * Tickit, an open source web based bug management tool.
 * 
 * Copyright (C) 2013  Tickit Project <http://tickit.io>
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

namespace Tickit\Bundle\ClientBundle\Manager;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NoResultException;
use Tickit\Bundle\ClientBundle\Entity\Client;
use Tickit\Bundle\ClientBundle\Entity\Repository\ClientRepository;
use Tickit\CoreBundle\Event\Dispatcher\AbstractEntityEventDispatcher;
use Tickit\CoreBundle\Manager\AbstractManager;

/**
 * Client Manager.
 *
 * Responsible for managing Client entities in the application.
 *
 * @package Tickit\Bundle\ClientBundle\Manager
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class ClientManager extends AbstractManager
{
    /**
     * The client repository
     *
     * @var ClientRepository
     */
    private $clientRepository;

    /**
     * Constructor.
     *
     * @param ClientRepository              $clientRepository The client repository
     * @param EntityManagerInterface        $em               An entity manager
     * @param AbstractEntityEventDispatcher $eventDispatcher  An event dispatcher
     */
    public function __construct(
        ClientRepository $clientRepository,
        EntityManagerInterface $em,
        AbstractEntityEventDispatcher $eventDispatcher
    ) {
        $this->clientRepository = $clientRepository;

        parent::__construct($em, $eventDispatcher);
    }

    /**
     * Gets the entity repository.
     *
     * This method returns the entity repository that is associated with this manager's entity.
     *
     * @return ClientRepository
     */
    public function getRepository()
    {
        return $this->clientRepository;
    }

    /**
     * Finds a client by ID
     *
     * @param integer $id The ID to find the client by
     *
     * @return Client
     */
    public function find($id)
    {
        try {
            return $this->getRepository()->find($id);
        } catch (NoResultException $e) {
            return null;
        }
    }

    /**
     * Returns the original Client from the entity manager.
     *
     * This method takes an entity and returns a copy in its original state
     * from the entity manager. This is used when dispatching entity update
     * events, so a before and after comparison can take place.
     *
     * @param object $entity The entity in its current state
     *
     * @return object
     */
    protected function fetchEntityInOriginalState($entity)
    {
        return $this->clientRepository->find($entity->getId());
    }
}
