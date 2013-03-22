<?php

namespace Tickit\ProjectBundle\Event;

use Tickit\CoreBundle\Event\AbstractVetoableEvent;
use Tickit\CoreBundle\Event\Interfaces\EntityAwareEventInterface;
use Tickit\ProjectBundle\Entity\Project;
use Tickit\ProjectBundle\Interfaces\ProjectAwareInterface;

/**
 * Event fired before a project is updated in the application
 *
 * @package Tickit\ProjectBundle\Event
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class BeforeUpdateEvent extends AbstractVetoableEvent implements ProjectAwareInterface, EntityAwareEventInterface
{
    /**
     * The project that is to be updated
     *
     * @var Project
     */
    protected $project;

    /**
     * Constructor.
     *
     * @param Project $project The project entity that is to be updated
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

    /**
     * Gets the entity associated with this event
     *
     * @return Project
     */
    public function getEntity()
    {
        return $this->getProject();
    }

    /**
     * Sets the entity associated with this event
     *
     * @param object $entity The entity to attach to the event
     */
    public function setEntity($entity)
    {
        $this->setProject($entity);
    }
}
