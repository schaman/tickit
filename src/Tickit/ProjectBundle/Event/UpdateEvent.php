<?php

namespace Tickit\ProjectBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use Tickit\ProjectBundle\Entity\Project;
use Tickit\ProjectBundle\Interfaces\ProjectAwareInterface;

/**
 * Event dispatched when a project is updated
 *
 * Event name: "tickit_project.event.update"
 *
 * @package Tickit\ProjectBundle\Event
 * @author  James Halsall <jhalsall@rippleffect.com>
 */
class UpdateEvent extends Event implements ProjectAwareInterface
{
    /**
     * The project that has been updated
     *
     * @var Project
     */
    protected $project;

    /**
     * Constructor.
     *
     * @param Project $project The project that has been updated
     */
    public function __construct(Project $project)
    {
        $this->project = null;
    }

    /**
     * Gets the project on this object
     *
     * @return Project
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * Sets the project on this object
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
