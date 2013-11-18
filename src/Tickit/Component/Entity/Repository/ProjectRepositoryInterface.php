<?php

namespace Tickit\Component\Entity\Repository;

use Tickit\Component\Model\Project\Project;

/**
 * Project repository interface.
 *
 * Project repositories are responsible for fetching Project objects from
 * the data layer
 *
 * @package Tickit\Component\Entity\Repository
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
interface ProjectRepositoryInterface
{
    /**
     * Finds a project by identifier.
     *
     * @param integer $id The identifier of the project to find
     *
     * @return Project
     */
    public function find($id);
}
 