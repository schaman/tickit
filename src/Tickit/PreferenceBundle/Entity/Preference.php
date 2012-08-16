<?php
namespace Tickit\PreferenceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * The Preference entity represents either a User preference of a System preference in the application
 *
 * @ORM\Entity(repositoryClass="Tickit\PreferenceBundle\Entity\Repository\PreferenceRepository")
 * @ORM\Table(name="preferences")
 */
class Preference
{
    //create constants here to map to setting names

    const TYPE_USER = 'user';
    const TYPE_SYSTEM = 'system';

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    protected $name;

    /**
     * @ORM\Column(name="system_name", type="string", length=100)
     */
    protected $systemName;

    /**
     * @ORM\Column(name="default_value", type="string", length=250)
     */
    protected $defaultValue;

    /**
     * @ORM\Column(type="string", length=8)
     */
    protected $type;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets the system friendly name
     *
     * @param string $name
     *
     * @return void
     */
    public function setSystemName($name)
    {
        $this->systemName = str_replace(' ', '', $name);
    }

    /**
     * Gets the system friendly name
     *
     * @return string
     */
    public function getSystemName()
    {
        return $this->systemName;
    }

    /**
     * Sets the default value for this preference
     *
     * @param string $defaultValue
     */
    public function setDefaultValue($defaultValue)
    {
        $this->defaultValue = $defaultValue;
    }

    /**
     * Gets the default value for this preference
     *
     * @return string 
     */
    public function getDefaultValue()
    {
        return $this->defaultValue;
    }


    /**
     * Sets the type
     *
     * @param string $type
     *
     * @throws \InvalidArgumentException
     * @return void
     */
    public function setType($type)
    {
        if (!in_array($type, array(self::TYPE_USER, self::TYPE_SYSTEM))) {
            throw new \InvalidArgumentException('An invalid type has been specified in Preference entity');
        }
        $this->type = $type;
    }

    /**
     * Returns the type of the preference
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }
}