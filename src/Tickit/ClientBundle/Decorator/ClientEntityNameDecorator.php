<?php

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
 