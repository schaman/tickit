<?php

namespace Tickit\ProjectBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * The AbstractAttributeValue entity represents a value associated with an attribute for a specific project
 *
 * @package Tickit\ProjectBundle\Entity
 * @author  James Halsall <james.t.halsall@googlemail.com>
 *
 * @ORM\Entity
 * @ORM\Table(name="project_attribute_values")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="type", type="string")
 * @ORM\DiscriminatorMap({
 *     "literal" = "LiteralAttributeValue",
 *     "choice" = "ChoiceAttributeValue",
 *     "entity" = "EntityAttributeValue"
 * })
 */
abstract class AbstractAttributeValue implements AttributeValueInterface
{
    /**
     * The project that this attribute value is associated with
     *
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Project", inversedBy="attributes")
     * @ORM\JoinColumn(name="project_id", referencedColumnName="id")
     */
    protected $project;

    /**
     * Gets the attribute type
     *
     * @return string
     */
    abstract public function getType();
}
