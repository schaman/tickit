<?php

namespace Tickit\ProjectBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * The ProjectAttributeValue entity represents a value associated with an attribute for a specific project
 *
 * @package Tickit\ProjectBundle\Entity
 * @author  James Halsall <james.t.halsall@googlemail.com>
 *
 * @ORM\Entity
 * @ORM\Table(name="project_attribute_values")
 */
class ProjectAttributeValue implements AttributeValueInterface
{
    /**
     * The attribute this value is for
     *
     * @ORM\Id
     * @ORM\OneToOne(targetEntity="ProjectAttribute")
     * @ORM\JoinColumn(name="project_attribute_id", referencedColumnName="id")
     */
    protected $attribute;

    /**
     * The project that this attribute value is associated with
     *
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Project", inversedBy="attributes")
     * @ORM\JoinColumn(name="project_id", referencedColumnName="id")
     */
    protected $project;

    /**
     * The attribute value
     *
     * @ORM\Column(type="string", length=500)
     */
    protected $value;

    /**
     * Gets the associated attribute object
     *
     * @return ProjectAttribute
     */
    public function getAttribute()
    {
        return $this->attribute;
    }

    /**
     * Gets the attribute value
     *
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }
}
