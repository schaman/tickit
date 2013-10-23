<?php

namespace Tickit\PreferenceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * SystemPreferenceValue entity.
 *
 * Represents a value for a system preference in the application.
 *
 * @package Tickit\PreferenceBundle\Entity
 * @author  James Halsall <james.t.halsall@googlemail.com>
 *
 * @ORM\Entity
 * @ORM\Table(name="system_preferences")
 */
class SystemPreferenceValue
{
    /**
     * The preference that this value relates to
     *
     * @var Preference
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Preference")
     * @ORM\JoinColumn(name="preference_id", referencedColumnName="id")
     */
    protected $preference;

    /**
     * The value for the associated preference
     *
     * @var string
     * @ORM\Column(type="text")
     */
    protected $value;

    /**
     * The date and time that this preference value was created
     *
     * @var \DateTime
     * @ORM\Column(type="datetime")
     * @Gedmo\Timestampable(on="create")
     */
    protected $created;

    /**
     * The date and time that this preference value was updated
     *
     * @var \DateTime
     * @ORM\Column(type="datetime")
     * @Gedmo\Timestampable(on="update")
     */
    protected $updated;


    /**
     * Sets the created time as an instance of DateTime
     *
     * @param \DateTime $created The DateTime that this preference value was updated
     *
     * @return SystemPreferenceValue
     */
    public function setCreated(\DateTime $created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Gets the created time as an instance of DateTime
     *
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Sets the Preference object
     *
     * @param Preference $preference The preference that this value relates to
     *
     * @return SystemPreferenceValue
     */
    public function setPreference(Preference $preference)
    {
        $this->preference = $preference;

        return $this;
    }

    /**
     * Gets the Preference object
     *
     * @return Preference
     */
    public function getPreference()
    {
        return $this->preference;
    }

    /**
     * Sets the updated time as an instance of DateTime
     *
     * @param \DateTime $updated The updated time for this preference value
     *
     * @return SystemPreferenceValue
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;
    }

    /**
     * Gets the updated time as an instance of DateTime
     *
     * @return \DateTime
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * Sets the value for this system preference
     *
     * @param mixed $value The new preference value
     *
     * @return SystemPreferenceValue
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Gets the value for this system preference
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }
}
