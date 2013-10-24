<?php

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
 