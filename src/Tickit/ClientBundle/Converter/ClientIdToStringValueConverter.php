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

namespace Tickit\ClientBundle\Converter;

use Doctrine\ORM\EntityNotFoundException;
use Tickit\ClientBundle\Decorator\ClientEntityNameDecorator;
use Tickit\ClientBundle\Entity\Client;
use Tickit\ClientBundle\Manager\ClientManager;
use Tickit\CoreBundle\Form\Type\Picker\EntityConverterInterface;

/**
 * ClientIdToStringValueConverter
 *
 * @package Tickit\ClientBundle\Converter
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class ClientIdToStringValueConverter implements EntityConverterInterface
{
    /**
     * The client manager
     *
     * @var ClientManager
     */
    private $manager;

    /**
     * The client decorator
     *
     * @var ClientEntityNameDecorator
     */
    private $decorator;

    /**
     * Constructor.
     *
     * @param ClientManager             $clientManager The client manager
     * @param ClientEntityNameDecorator $decorator     The client decorator
     */
    public function __construct(ClientManager $clientManager, ClientEntityNameDecorator $decorator)
    {
        $this->manager = $clientManager;
        $this->decorator = $decorator;
    }

    /**
     * Converts an entity or entity representation into a descriptive scalar representation
     * for form use.
     *
     * The method should throw an EntityNotFoundException in the event that an entity is invalid
     * or cannot be found.
     *
     * @param mixed $clientId The client identifier to convert
     *
     * @throws \Doctrine\ORM\EntityNotFoundException
     *
     * @return Client
     */
    public function convert($clientId)
    {
        $client = $this->manager->find($clientId);

        if (null === $client) {
            throw new EntityNotFoundException(sprintf('Client not found for ID (%d)', $clientId));
        }

        return $this->decorator->decorate($client);
    }
}
