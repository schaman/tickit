<?php

namespace Tickit\ProjectBundle\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Tickit\CoreBundle\Controller\AbstractCoreController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Tickit\ProjectBundle\Entity\Project;
use Tickit\ProjectBundle\Entity\Repository\ProjectRepository;
use Tickit\ProjectBundle\Form\Type\EditFormType;
use Tickit\ProjectBundle\Manager\ProjectManager;

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
        /** @var ProjectManager $manager */
        $manager = $this->get('tickit_project.manager');
        $repo = $manager->getRepository();

        $project = $repo->find($id);

        if (empty($project)) {
            throw $this->createNotFoundException('Project not found');
        }

        $formType = new EditFormType($project);
        $form = $this->createForm($formType, $project);

        if ('POST' === $this->getRequest()->getMethod()) {
            $form->bind($this->getRequest());
            $project = $form->getData();
            $manager->update($project);

            $this->get('session')->getFlashbag()->add('notice', 'Your changes have been saved successfully');

            $route = $this->get('router')->generate('project_edit', array('id' => $id));
            return new RedirectResponse($route);
        }

        return array('form' => $form->createView());
    }

    /**
     * Updates a project
     *
     * @param integer $id The ID of the project to update
     *
     * @throws NotFoundHttpException If no project was found for the given ID
     *
     * @return RedirectResponse
     */
    public function updateAction($id)
    {
        $project = new Project();

        $formType = new EditFormType();
        $form = $this->createForm($formType, $project);
        $form->bind($this->getRequest());
    }
}