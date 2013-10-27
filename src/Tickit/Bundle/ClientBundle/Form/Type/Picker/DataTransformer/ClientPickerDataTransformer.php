<?php

namespace Tickit\Bundle\ClientBundle\Form\Type\Picker\DataTransformer;

use Tickit\Bundle\ClientBundle\Manager\ClientManager;
use Tickit\CoreBundle\Form\Type\Picker\DataTransformer\AbstractPickerDataTransformer;

/**
 * Client Picker data transformer.
 *
 * @package Tickit\Bundle\ClientBundle\Form\Type\Picker\DataTransformer
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class ClientPickerDataTransformer extends AbstractPickerDataTransformer
{
    /**
     * The client manager
     *
     * @var ClientManager
     */
    private $manager;

    /**
     * @param ClientManager $clientManager
     */
    public function __construct(ClientManager $clientManager)
    {
        $this->manager = $clientManager;
    }

    /**
     * {@inheritDoc}
     *
     * @return string
     */
    protected function getEntityIdentifier()
    {
        return 'id';
    }

    /**
     * Returns an entity instance by identifier
     *
     * @param mixed $identifier The entity identifier value
     *
     * @return mixed
     */
    protected function findEntityByIdentifier($identifier)
    {
        return $this->manager->find($identifier);
    }
}
