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
     * @var Project
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

    /**
     * Sets the project that this attribute value is associated with
     *
     * @param Project $project The new project
     *
     * @return AbstractAttributeValue
     */
    public function setProject(Project $project)
    {
        $this->project = $project;

        return $this;
    }

    /**
     * Gets the project that this attribute value is for
     *
     * @return Project
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * Factory method for creating new instances of AttributeValue entities
     *
     * @param string $type The attribute value type to create
     *
     * @throws \InvalidArgumentException If an invalid type was provided
     *
     * @return AbstractAttributeValue
     */
    public static function factory($type)
    {
        if (!in_array($type, AbstractAttribute::getAvailableTypes())) {
            throw new \InvalidArgumentException(
                sprintf('An invalid type was provided (%s)', $type)
            );
        }

        switch ($type) {
            case AbstractAttribute::TYPE_CHOICE:
                $attribute = new ChoiceAttributeValue();
                break;
            case AbstractAttribute::TYPE_ENTITY:
                $attribute = new EntityAttributeValue();
                break;
            default:
                $attribute = new LiteralAttributeValue();
        }

        return $attribute;
    }
}
