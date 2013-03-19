<?php

namespace Tickit\CoreBundle\Entity\Interfaces;

/**
 * Interface used to describe entities that can be deleted
 *
 * @package Tickit\CoreBundle\Entity\Interfaces
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
interface DeletableEntityInterface
{
    /**
     * Handles the deletion the entity.
     *
     * This method should not actually delete the entity, but preferably should
     * just update the entity with the relevant flags/data that will mark it as
     * being deleted.
     *
     * @return mixed
     */
    function delete();
}
