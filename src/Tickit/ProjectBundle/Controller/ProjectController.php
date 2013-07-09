<?php

namespace Tickit\ProjectBundle\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Tickit\CoreBundle\Controller\AbstractCoreController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Tickit\ProjectBundle\Entity\Project;
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
     * Create action.
     *
     * Serves a JsonResponse containing the form markup template
     *
     * @return JsonResponse
     */
    public function createAction()
    {
        $responseData = array('success' => false, 'errors' => array());
        $project = new Project();

        $attributes = $this->get('tickit_project.attribute_manager')->getAttributeValuesForProject($project);
        $project->setAttributes($attributes);

        $form = $this->createForm($this->get('tickit_project.form.project'), $project);

        if ('POST' == $this->getRequest()->getMethod()) {
            $form->submit($this->getRequest());

            if ($form->isValid()) {
                $project = $form->getData();
                $manager = $this->get('tickit_project.manager');
                $manager->create($project);

                $responseData['success'] = true;
                $responseData['returnUrl'] = $this->generateUrl('project_index');
            } else {
                $responseData['errors'] = $form->getErrors();
            }
        }

        return new JsonResponse($responseData);
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

        $formType = $this->get('tickit_project.form.project');
        $form = $this->createForm($formType, $project);

        if ('POST' === $this->getRequest()->getMethod()) {
            $form->submit($this->getRequest());

            if ($form->isValid()) {
                $project = $form->getData();
                $manager->update($project);

                $flash = $this->get('tickit.flash_messages');
                $flash->addEntityUpdatedMessage('project');
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
        $this->checkCsrfToken($token, 'delete_project');

        /** @var ProjectManager $manager  */
        $manager = $this->get('tickit_project.manager');
        $project = $manager->getRepository()->find($id);

        if (empty($project)) {
            throw $this->createNotFoundException('Project not found');
        }

        $manager->delete($project);

        $flash = $this->get('tickit.flash_messages');
        $flash->addEntityDeletedMessage('project');

        $route = $this->generateUrl('project_index');

        return $this->redirect($route);
    }
}
