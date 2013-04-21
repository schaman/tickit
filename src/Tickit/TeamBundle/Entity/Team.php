<?php

namespace Tickit\TeamBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Team entity.
 *
 * Represents a team of users in the application.
 *
 * These are not the same as Groups.
 *
 * @ORM\Entity(repositoryClass="Tickit\TeamBundle\Entity\Repository\TeamRepository")
 * @ORM\Table(name="teams")
 */
class Team
{
   /**
    * Unique identifier for this team
    *
    * @ORM\Id
    * @ORM\Column(type="integer")
    * @ORM\GeneratedValue(strategy="AUTO")
    */
    protected $id;

    /**
     * The name of the team
     *
     * @var string
     * @ORM\Column(type="string", length=120)
     */
    protected $name;

    /**
     * The datetime that this team was created
     *
     * @var \DateTime
     * @ORM\Column(type="datetime")
     * @Gedmo\Timestampable(on="create")
     */
    protected $created;

    /**
     * The datetime that this team was updated
     *
     * @var \DateTime
     * @ORM\Column(type="datetime")
     * @Gedmo\Timestampable(on="update")
     */
    protected $updated;

    /**
     * Gets the identifier for this team
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets the name for this team
     *
     * @param string $name The new name
     *
     * @return Team
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Gets the team name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets the created time as an instance of DateTime
     *
     * @param \DateTime $created The creation time
     *
     * @return Team
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Gets the created time as an instance of DateTime
     *
     * @return mixed
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Sets the updated time as an instance of DateTime
     *
     * @param \DateTime $updated The update time
     *
     * @return Team
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;

        return $this;
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
}
