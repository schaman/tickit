<?php

namespace Tickit\ProjectBundle\Interfaces;

use Tickit\ProjectBundle\Entity\Project;

/**
 * Interface for classes that are Project aware
 *
 * @package Tickit\ProjectBundle\Interfaces
 * @author  James Halsall <jhalsall@rippleffect.com>
 * @see     Tickit\ProjectBundle\Entity\Project
 */
interface ProjectAwareInterface
{
    /**
     * Gets the project on this object
     *
     * @return Project
     */
    public function getProject();

    /**
     * Sets the project on this object
     *
     * @param Project $project The project to set
     *
     * @return mixed
     */
    public function setProject(Project $project);
}
