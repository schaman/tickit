<?php

namespace Tickit\CoreBundle\Flash;

/**
 * Interface for flash message generators.
 *
 * Provides a way of encapsulating all flash message notifications across the
 * application.
 *
 * @package Tickit\CoreBundle\Flash
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
interface GeneratorInterface
{
    /**
     * Generates and returns flash message for the creation of entities
     *
     * @param string $entityName The name of the entity that was created
     *
     * @return string
     */
    public function getEntityCreatedMessage($entityName);

    /**
     * Generates and returns flash message for the creation of entities
     *
     * @param string $entityName The name of the entity that was updated
     *
     * @return string
     */
    public function getEntityUpdatedMessage($entityName);

    /**
     * Generates and returns flash message for the creation of entities
     *
     * @param string $entityName The name of the entity that was deleted
     *
     * @return string
     */
    public function getEntityDeletedMessage($entityName);
}
