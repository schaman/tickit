<?php

namespace Tickit\ProjectBundle\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Entity attribute implementation
 *
 * Represents a project attribute that has a predefined list of entities which
 * are used as choices.
 *
 * @package Tickit\ProjectBundle\Entity
 * @author  James Halsall <james.t.halsall@googlemail.com>
 *
 * @ORM\Entity
 */
class EntityAttribute extends AbstractAttribute
{
    /**
     * Attribute values that use this entity attribute
     *
     * @var Collection
     * @ORM\OneToMany(targetEntity="EntityAttributeValue", mappedBy="attribute")
     */
    protected $values;

    /**
     * The entity class that this attribute is for
     *
     * @var string
     * @ORM\Column(type="string", length=200)
     */
    protected $entity;

    /**
     * Gets the attribute type
     *
     * @return string
     */
    public function getType()
    {
        return static::TYPE_ENTITY;
    }

    /**
     * Gets the entity class name that this attribute is for
     *
     * @return string
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * Sets the entity class name
     *
     * @param string $entity The new entity class name
     *
     * @return EntityAttribute
     */
    public function setEntity($entity)
    {
        $this->entity = $entity;

        return $this;
    }
}
