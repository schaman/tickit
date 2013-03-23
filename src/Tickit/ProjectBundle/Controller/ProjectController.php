<?php

namespace Tickit\ProjectBundle\Controller;

use Tickit\CoreBundle\Controller\AbstractCoreController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Project controller.
 *
 * Responsible for handling requests related to projects
 *
 * @package Tickit\ProjectBundle\Controller
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class ProjectController extends AbstractCoreController
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