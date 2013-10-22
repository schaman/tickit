<?php

namespace Tickit\ProjectBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use DateTime;
use Tickit\ClientBundle\Entity\Client;

/**
 * Project entity
 *
 * Represents an application/website/product within the application
 *
 * @ORM\Entity(repositoryClass="Tickit\ProjectBundle\Entity\Repository\ProjectRepository")
 * @ORM\Table(name="projects")
 */
class Project
{
    /**
     * The unique identifier for this project
     *
     * @var integer
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * The name of this project
     *
     * @var string
     * @ORM\Column(type="string", length=100)
     */
    protected $name;

    /**
     * The client that this project relates to
     *
     * @var Client
     * @ORM\JoinColumn(name="client_id", referencedColumnName="id")
     * @ORM\ManyToOne(targetEntity="Tickit\ClientBundle\Entity\Client", inversedBy="projects")
     */
    protected $client;

    /**
     * Tickets related to this project
     *
     * @var Collection
     * @ORM\OneToMany(targetEntity="Tickit\TicketBundle\Entity\Ticket", mappedBy="project")
     */
    protected $tickets;

    /**
     * Attribute values for this project
     *
     * @var Collection
     * @ORM\OneToMany(targetEntity="AbstractAttributeValue", mappedBy="project", cascade="persist")
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
     * Constructor.
     */
    public function __construct()
    {
        $this->attributes = new ArrayCollection();
    }

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
     *
     * @return Project
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
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
     * Sets attributes on this project
     *
     * @param Collection $attributes The new collection of attributes
     *
     * @return Project
     */
    public function setAttributes(Collection $attributes)
    {
        $this->attributes = $attributes;

        return $this;
    }

    /**
     * Gets the attributes for this project
     *
     * @return Collection
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * Sets the time this project was deleted
     *
     * @param DateTime $deletedAt The date time that this project was deleted
     *
     * @return Project
     */
    public function setDeletedAt(DateTime $deletedAt)
    {
        $this->deletedAt = $deletedAt;

        return $this;
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

    /**
     * Set tickets on this project
     *
     * @param Collection $tickets The tickets collection
     *
     * @return Project
     */
    public function setTickets(Collection $tickets)
    {
        $this->tickets = $tickets;

        return $this;
    }

    /**
     * Sets the updated time
     *
     * @param \DateTime $updated The updated time
     *
     * @return Project
     */
    public function setUpdated(\DateTime $updated)
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * Sets the created time
     *
     * @param \DateTime $created The created time
     *
     * @return Project
     */
    public function setCreated(\DateTime $created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Sets the project's Client
     *
     * @param Client $client The client
     *
     * @return Project
     */
    public function setClient($client)
    {
        $this->client = $client;

        return $this;
    }
}
