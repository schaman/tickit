<?php

namespace Tickit\ProjectBundle\Manager;

use Tickit\ProjectBundle\Entity\AbstractAttribute;

/**
 * Attribute manager.
 *
 * Responsible for project attribute entities in the application.
 *
 * @package Tickit\ProjectBundle\Manager
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class AttributeManager
{
    /**
     * Creates an Attribute entity by persisting it and flushing changes to the entity manager
     *
     * @param AbstractAttribute $entity The Attribute entity to persist
     * @param boolean   $flush  False to prevent the changes being flushed, defaults to true
     */
    public function create(AbstractAttribute $entity, $flush = true)
    {
        var_dump($entity); die;
    }
}
