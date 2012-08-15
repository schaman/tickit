<?php

namespace Tickit\TicketBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Tickit\UserBundle\Entity\User;

/**
 * The User entity represents an individual user within the application
 *
 * @ORM\Entity
 * @ORM\Table(name="tickets")
 */
class Ticket
{

    /**
     * @ORM\Id
     * @ORM\Column(type="bigint")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    protected $title;

    /**
     * @ORM\OneToMany(targetEntity="Comment", mappedBy="ticket")
     */
    protected $comments;

    /**
     * @ORM\OneToMany(targetEntity="TicketAttachment", mappedBy="ticket")
     */
    protected $attachments;

    /**
     * @ORM\OneToMany(targetEntity="TicketUserSubscription", mappedBy="user")
     */
    protected $ticketSubscriptions;

    /**
     * @ORM\ManyToOne(targetEntity="Tickit\ProjectBundle\Entity\Project")
     * @ORM\JoinColumn(name="project_id", referencedColumnName="id")
     */
    protected $project;

    /**
     * @ORM\ManyToOne(targetEntity="Tickit\TicketBundle\Entity\TicketStatus")
     * @ORM\JoinColumn(name="ticket_status_id", referencedColumnName="id")
     */
    protected $status;

    /**
     * @ORM\Column(type="string", length=4000)
     */
    protected $description;

    /**
     * @ORM\Column(name="replication_steps", type="string", length=4000)
     */
    protected $replicationSteps;

    /**
     * @ORM\Column(name="estimated_hours", type="float")
     */
    protected $estimatedHours;

    /**
     * @ORM\Column(name="actual_hours", type="float")
     */
    protected $actualHours;

    /**
     * @ORM\ManyToOne(targetEntity="Tickit\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="reported_by_id", referencedColumnName="id")
     */
    protected $reportedBy;

    /**
     * @ORM\ManyToOne(targetEntity="Tickit\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="assigned_to_id", referencedColumnName="id")
     */
    protected $assignedTo;

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
     * Gets the id of this ticket
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets the title of this ticket
     *
     * @param $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * Gets the title of this ticket
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }


    /**
     * Sets the ticket status
     *
     * @param TicketStatus $status
     */
    public function setStatus(TicketStatus $status)
    {
        $this->status = $status;
    }


    /**
     * Returns the TicketStatus object representing the status of this ticket
     *
     * @return TicketStatus
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Sets the user who this ticket is assigned to
     *
     * @param User $assignedTo
     */
    public function setAssignedTo(User $assignedTo)
    {
        $this->assignedTo = $assignedTo;
    }

    /**
     * Gets the user who this ticket is assigned to
     *
     * @return User
     */
    public function getAssignedTo()
    {
        return $this->assignedTo;
    }

    /**
     * Sets the ticket description
     *
     * @param $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * Gets the ticket description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Sets the replication steps for this ticket
     *
     * @param string $replicationSteps
     */
    public function setReplicationSteps($replicationSteps)
    {
        $this->replicationSteps = $replicationSteps;
    }

    /**
     * Gets the replication steps for this ticket
     *
     * @return string
     */
    public function getReplicationSteps()
    {
        return $this->replicationSteps;
    }

    /**
     * Sets the "reported by" user on this ticket
     *
     * @param User $reportedBy
     */
    public function setReportedBy(User $reportedBy)
    {
        $this->reportedBy = $reportedBy;
    }


    /**
     * Gets the user that reported this ticket
     *
     * @return User
     */
    public function getReportedBy()
    {
        return $this->reportedBy;
    }

    /**
     * Gets the updated time as an instance of DateTime object
     *
     * @return \DateTime
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * Gets the created time as an instance of DateTime object
     *
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }


}