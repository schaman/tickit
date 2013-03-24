<?php

namespace Tickit\ProjectBundle\Controller;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Tickit\CoreBundle\Controller\AbstractCoreController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Tickit\ProjectBundle\Entity\Repository\ProjectRepository;
use Tickit\ProjectBundle\Form\Type\EditFormType;

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

    /**
     * Loads the edit project page
     *
     * @param integer $id The ID of the project to edit
     *
     * @Template("TickitProjectBundle:Project:edit.html.twig")
     *
     * @throws NotFoundHttpException If no project was found for the given ID
     *
     * @return array
     */
    public function editAction($id)
    {
        /** @var ProjectRepository $repo */
        $repo = $this->get('tickit_project.manager')
                        ->getRepository();

        $project = $repo->find($id);

        if (empty($project)) {
            throw $this->createNotFoundException('Project not found');
        }

        $formType = new EditFormType($project);
        $form = $this->createForm($formType, $project);

        return array('form' => $form->createView());
    }
}