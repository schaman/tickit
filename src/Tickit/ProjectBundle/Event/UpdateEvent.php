<?php

namespace Tickit\ProjectBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use Tickit\CoreBundle\Event\Interfaces\UpdateEventInterface;
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
class UpdateEvent extends Event implements UpdateEventInterface, ProjectAwareInterface
{
    /**
     * The project that has been updated
     *
     * @var Project
     */
    protected $project;

    /**
     * The original project entity before updates
     *
     * @var Project
     */
    protected $originalProject;

    /**
     * Constructor.
     *
     * @param Project $project         The project that has been updated
     * @param Project $originalProject The original project entity before updates
     */
    public function __construct(Project $project, Project $originalProject)
    {
        $this->setProject($project);
        $this->setOriginalProject($originalProject);
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

    /**
     * Sets the original project on this object - before updates applied
     *
     * @param Project $originalProject
     */
    private function setOriginalProject($originalProject)
    {
        $this->originalProject = $originalProject;
    }

    /**
     * Gets the original project entity on this object - before updates applied
     *
     * @return Project
     */
    public function getOriginalEntity()
    {
        return $this->originalProject;
    }
}
