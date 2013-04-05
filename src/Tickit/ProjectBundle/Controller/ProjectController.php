<?php

namespace Tickit\ProjectBundle\Controller;

use Symfony\Component\Form\Extension\Csrf\CsrfProvider\CsrfProviderInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Tickit\CoreBundle\Controller\AbstractCoreController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Tickit\ProjectBundle\Form\Type\ProjectFormType;
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

        $token = $this->get('form.csrf_provider')->generateCsrfToken('delete_project');

        return array('projects' => $projects, 'token' => $token);
    }

    /**
     * Loads the add project page
     *
     * @Template("TickitProjectBundle:Project:add.html.twig")
     *
     * @return array|RedirectResponse
     */
    public function addAction()
    {
        $formType = new ProjectFormType();
        $form = $this->createForm($formType);

        if ('POST' == $this->getRequest()->getMethod()) {
            $form->bind($this->getRequest());

            if ($form->isValid()) {
                $project = $form->getData();

                /** @var ProjectManager $manager  */
                $manager = $this->get('tickit_project.manager');
                $manager->create($project);

                $generator = $this->get('tickit.flash_messages');
                $this->get('session')->getFlashBag()->add('notice', $generator->getEntityCreatedMessage('project'));
                $route = $this->generateUrl('project_index');

                return $this->redirect($route);
            }
        }

        return array('form' => $form->createView());
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

        $formType = new ProjectFormType();
        $form = $this->createForm($formType, $project);

        if ('POST' === $this->getRequest()->getMethod()) {
            $form->bind($this->getRequest());

            if ($form->isValid()) {
                $project = $form->getData();
                $manager->update($project);

                $generator = $this->get('tickit.flash_messages');
                $this->get('session')->getFlashbag()->add('notice', $generator->getEntityUpdatedMessage('project'));
            }
        }

        return array('form' => $form->createView());
    }

    /**
     * Deletes a project from the application.
     *
     * @param integer $id The ID of the project to delete
     *
     * @throws NotFoundHttpException If no project was found for the given ID or an invalid CSRF token is provided
     *
     * @return RedirectResponse
     */
    public function deleteAction($id)
    {
        $token = $this->getRequest()->query->get('token');
        /** @var CsrfProviderInterface $tokenProvider  */
        $tokenProvider = $this->get('form.csrf_provider');

        if (!$tokenProvider->isCsrfTokenValid('delete_project', $token)) {
            throw $this->createNotFoundException('Invalid CSRF token');
        }

        /** @var ProjectManager $manager  */
        $manager = $this->get('tickit_project.manager');
        $project = $manager->getRepository()->find($id);

        if (empty($project)) {
            throw $this->createNotFoundException('Project not found');
        }

        $manager->delete($project);

        $generator = $this->get('tickit.flash_messages');
        $this->get('session')->getFlashBag()->add('notice', $generator->getEntityDeletedMessage('project'));

        $route = $this->generateUrl('project_index');

        return $this->redirect($route);
    }
}