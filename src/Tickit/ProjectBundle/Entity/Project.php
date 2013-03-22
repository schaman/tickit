<?php

namespace Tickit\ProjectBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use DateTime;

/**
 * The project entity represents an application/website/product within the application
 *
 * @ORM\Entity(repositoryClass="Tickit\ProjectBundle\Entity\Repository\ProjectRepository")
 * @ORM\Table(name="projects")
 */
class Project
{
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
     * @ORM\OneToMany(targetEntity="Tickit\TicketBundle\Entity\Ticket", mappedBy="project")
     */
    protected $tickets;

    /**
     * @ORM\OneToMany(targetEntity="ProjectAttributeValue", mappedBy="project")
     */
    protected $attributes;

    /**
     * @ORM\Column(type="datetime")
     * @Gedmo\Timestampable(on="create")
     */
    protected $created;

    /**
     * @ORM\Column(type="datetime")
     * @Gedmo\Timestampable(on="update")
     */
    protected $updated;

    /**
     * The time that this project was deleted (if any)
     *
     * @var string
     * @ORM\Column(name="deleted_at", type="datetime", nullable=true)
     */
    protected $deletedAt;


    /**
     * Gets the project ID
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets the project name
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Gets the project name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
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
     * Gets the updated time as an instance of DateTime
     *
     * @return \DateTime
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * Gets the time at which this project was deleted
     *
     * @return DateTime
     */
    public function getDeletedAt()
    {
        return $this->deletedAt;
    }

    /**
     * Sets the time this project was deleted
     *
     * @param DateTime $deletedAt The date time that this project was deleted
     *
     * @return void
     */
    public function setDeletedAt(DateTime $deletedAt)
    {
        $this->deletedAt = $deletedAt;
    }
}