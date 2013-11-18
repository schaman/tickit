<?php

namespace Tickit\Component\Entity\Repository;

/**
 * Attribute repository interface.
 *
 * Attribute repositories are responsible for fetching AbstractAttribute objects
 * from the data layer.
 *
 * @package Tickit\Component\Entity\Repository
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
interface AttributeRepositoryInterface
{
    /**
     * Returns a deep collection of all attributes
     *
     * This method includes all associated meta objects related to the attributes.
     *
     * @return mixed
     */
    public function findAllAttributes();
}
