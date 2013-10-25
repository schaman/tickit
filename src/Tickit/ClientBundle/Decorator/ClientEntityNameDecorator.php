<?php

namespace Tickit\ClientBundle\Decorator;

use Tickit\ClientBundle\Entity\Client;

/**
 * Client entity name decorator.
 *
 * Decorates a client entity with a name.
 *
 * @package Tickit\ClientBundle\Decorator
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class ClientEntityNameDecorator
{
    /**
     * Decorates a client entity
     *
     * @param Client $client The client ID to decorate
     *
     * @return string
     */
    public function decorate(Client $client)
    {
        return $client->getName();
    }
}
 