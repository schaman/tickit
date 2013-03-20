<?php

namespace Tickit\ProjectBundle\Event;

use Tickit\CoreBundle\Event\AbstractVetoableEvent;
use Tickit\ProjectBundle\Entity\Project;
use Tickit\ProjectBundle\Interfaces\ProjectAwareInterface;

/**
 * Event fired before a project is created in the application
 *
 * @package Tickit\ProjectBundle\Event
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class BeforeCreateEvent extends AbstractVetoableEvent implements ProjectAwareInterface
{
    /**
     * The project that is to be deleted
     *
     * @var Project
     */
    protected $project;

    /**
     * Constructor.
     *
     * @param Project $project The project entity that is to be created
     */
    public function __construct(Project $project)
    {
        $this->project = $project;
    }

    /**
     * Gets the project on this event
     *
     * @return Project
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * Sets the project on this event
     *
     * @param Project $project The project to set
     *
     * @return mixed
     */
    public function setProject(Project $project)
    {
        $this->project = $project;
    }
}
