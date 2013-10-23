<?php

namespace Tickit\ProjectBundle\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Entity representing a choice available for a ChoiceAttribute
 *
 * @package Tickit\ProjectBundle\Entity
 * @author  James Halsall <james.t.halsall@googlemail.com>
 *
 * @ORM\Entity(repositoryClass="Tickit\ProjectBundle\Entity\Repository\ChoiceAttributeChoiceRepository")
 * @ORM\Table(name="project_attribute_choices")
 */
class ChoiceAttributeChoice
{
    /**
     * The unique identifier for this choice
     *
     * @var integer
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * The attribute that this choice belongs to
     *
     * @var ChoiceAttribute
     * @ORM\ManyToOne(targetEntity="ChoiceAttribute", inversedBy="choices")
     * @ORM\JoinColumn(name="attribute_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $attribute;

    /**
     * Any values that have this choice selected
     *
     * @var Collection
     * @ORM\ManyToMany(targetEntity="ChoiceAttributeValue", mappedBy="value")
     */
    protected $values;

    /**
     * The name of this choice
     *
     * @var string
     * @ORM\Column(type="string", length=120)
     */
    protected $name;

    /**
     * Gets the unique identifier for this choice
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets the unique identifier
     *
     * @param integer $id The identifier
     *
     * @return ChoiceAttributeChoice
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Sets the attribute that this choice belongs to
     *
     * @param ChoiceAttribute $attribute The attribute
     *
     * @return ChoiceAttributeChoice
     */
    public function setAttribute($attribute)
    {
        $this->attribute = $attribute;

        return $this;
    }

    /**
     * Gets the attribute that this choice belongs to
     *
     * @return ChoiceAttribute
     */
    public function getAttribute()
    {
        return $this->attribute;
    }

    /**
     * Sets the name of this choice
     *
     * @param string $name The new name
     *
     * @return ChoiceAttributeChoice
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Gets the name of this choice
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * __toString() method
     *
     * @return string
     */
    public function __toString()
    {
        return $this->name;
    }
}
