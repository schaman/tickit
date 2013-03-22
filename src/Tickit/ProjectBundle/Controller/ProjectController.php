<?php

namespace Tickit\ProjectBundle\Controller;

use Tickit\CoreBundle\Controller\CoreController;

/**
 * Project controller.
 *
 * Responsible for handling requests related to projects
 *
 * @package Tickit\ProjectBundle\Controller
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class ProjectController extends CoreController
{
    /**
     * Lists all projects in the application
     *
     * @Template("TickitProjectBundle:Project:index.html.twig")
     *
     * @return array
     */
    public function indexAction()
    {
        $projects = $this->get('tickit_project.manager')
                         ->getRepository()
                         ->findProjects();

        return array('projects' => $projects);
    }
}