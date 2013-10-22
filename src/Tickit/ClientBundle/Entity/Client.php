<?php

namespace Tickit\ClientBundle\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Client entity.
 *
 * @package Tickit\ClientBundle\Entity
 * @author  James Halsall <james.t.halsall@googlemail.com>
 *
 * @ORM\Entity(repositoryClass="Tickit\ClientBundle\Entity\Repository\ClientRepository")
 * @ORM\Table(name="clients")
 */
class Client
{
    const STATUS_ACTIVE = 'active';
    const STATUS_ARCHIVED = 'archived';

    /**
     * The unique identifier for the client
     *
     * @var integer
     *
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * The name of the client
     *
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=120)
     */
    private $name;

    /**
     * The client url.
     *
     * This is usually the homepage for the client.
     *
     * @var string
     *
     * @ORM\Column(name="url", type="text")
     */
    private $url;

    /**
     * Additional notes for the client
     *
     * @var string
     *
     * @ORM\Column(name="notes", type="text")
     */
    private $notes;

    /**
     * The status of the client
     *
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=10)
     */
    private $status;

    /**
     * The date and time the client was created
     *
     * @var \DateTime
     * @ORM\Column(type="datetime")
     * @Gedmo\Timestampable(on="create")
     */
    private $created;

    /**
     * The date and time the client was last updated
     *
     * @var \DateTime
     * @ORM\Column(type="datetime")
     * @Gedmo\Timestampable(on="update")
     */
    private $updated;

    /**
     * Projects that belong to this client
     *
     * @var Collection
     * @ORM\OneToMany(targetEntity="Tickit\ProjectBundle\Entity\Project", mappedBy="client")
     */
    private $projects;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->setStatus(static::STATUS_ACTIVE);
    }

    /**
     * Get the unique identifier
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
     * @param integer $id The unique identifier
     *
     * @return Client
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Sets the client name
     *
     * @param string $name The new client name
     *
     * @return Client
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Gets the client name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets the client URL
     *
     * @param string $url The new client URL
     *
     * @return Client
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string 
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Sets client notes
     *
     * @param string $notes Client notes
     *
     * @return Client
     */
    public function setNotes($notes)
    {
        $this->notes = $notes;

        return $this;
    }

    /**
     * Gets client notes
     *
     * @return string 
     */
    public function getNotes()
    {
        return $this->notes;
    }

    /**
     * Sets the date and time that the client was created
     *
     * @param \DateTime $created The date time
     *
     * @return Client
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Gets the date and time that the client was created
     *
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Sets the date and time that the client was updated
     *
     * @param \DateTime $updated The date time
     *
     * @return Client
     */
    public function setUpdated(\DateTime $updated)
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * Gets the date and time that this client was updated
     *
     * @return \DateTime
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * Sets the status for the client
     *
     * @param string $status The new client status
     *
     * @throws \InvalidArgumentException If an invalid status is provided
     */
    public function setStatus($status)
    {
        if (!in_array($status, [static::STATUS_ACTIVE, static::STATUS_ARCHIVED])) {
            throw new \InvalidArgumentException(
                sprintf('An invalid status was provided (%s)', $status)
            );
        }

        $this->status = $status;
    }

    /**
     * Gets the status of the client
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }
}
