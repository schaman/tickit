<?php

namespace Tickit\ProjectBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Entity attribute implementation
 *
 * Represents a project attribute that has a predefined list of entities which
 * are used as choices.
 *
 * @package Tickit\ProjectBundle\Entity
 * @author  James Halsall <jhalsall@rippleffect.com>
 *
 * @ORM\Entity
 */
class EntityAttribute extends AbstractAttribute
{
    /**
     * Gets the attribute type
     *
     * @return string
     */
    public function getType()
    {
        return static::TYPE_ENTITY;
    }
}
