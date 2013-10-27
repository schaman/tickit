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

namespace Tickit\ClientBundle\Decorator;

use Tickit\ClientBundle\Entity\Client;
use Tickit\CoreBundle\Form\Type\Picker\EntityDecoratorInterface;

/**
 * Client entity name decorator.
 *
 * Decorates a client entity with a name.
 *
 * @package Tickit\ClientBundle\Decorator
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class ClientEntityNameDecorator implements EntityDecoratorInterface
{
    /**
     * Decorates a client entity
     *
     * @param Client $client The client ID to decorate
     *
     * @throws \InvalidArgumentException If the $client isn't the correct instance type
     *
     * @return string
     */
    public function decorate($client)
    {
        if (!$client instanceof Client) {
            throw new \InvalidArgumentException(
                sprintf(
                    'The $client must be an instance of Tickit\ClientBundle\Entity\Client, %s given',
                    gettype($client)
                )
            );
        }

        return $client->getName();
    }
}
