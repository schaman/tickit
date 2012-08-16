<?php

namespace Tickit\ProjectBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * The ProjectAttribute entity represents a specific attribute that is customisable per project
 *
 * @ORM\Entity
 * @ORM\Table(name="project_attributes")
 */
class ProjectAttribute implements AttributeInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=120)
     */
    protected $name;

    /**
     * @ORM\Column(name="default_value", type="string", length=500)
     */
    protected $defaultValue;

    /**
     * Gets the attribute ID
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets the name of this attribute
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Gets the name of this attribute
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets the default value for this attribute
     *
     * @param mixed $defaultValue
     */
    public function setDefaultValue($defaultValue)
    {
        $this->defaultValue = $defaultValue;
    }

    /**
     * Gets the default value for this attribute
     *
     * @return mixed
     */
    public function getDefaultValue()
    {
        return $this->defaultValue;
    }
}