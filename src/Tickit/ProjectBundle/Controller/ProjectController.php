<?php

namespace Tickit\ProjectBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Tickit\CoreBundle\Controller\AbstractCoreController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Tickit\ProjectBundle\Entity\Project;

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
     * String intention for deleting a project
     *
     * @const string
     */
    const CSRF_DELETE_INTENTION = 'delete_project';

    /**
     * Create action.
     *
     * Serves a JsonResponse containing the form markup template
     *
     * @return JsonResponse
     */
    public function createAction()
    {
        $responseData = array('success' => false);
        $project = new Project();

        $attributes = $this->get('tickit_project.attribute_manager')->getAttributeValuesForProject($project);
        $project->setAttributes($attributes);

        $form = $this->createForm($this->get('tickit_project.form.project'), $project);

        $form->submit($this->getRequest());

        if ($form->isValid()) {
            $project = $form->getData();
            $manager = $this->get('tickit_project.manager');
            $manager->create($project);

            $responseData['success'] = true;
            $responseData['returnUrl'] = $this->generateUrl('project_index');
        } else {
            $responseData['form'] = $this->render(
                'TickitProjectBundle:Project:create.html.twig',
                array('form' => $form->createView())
            )->getContent();
        }

        return new JsonResponse($responseData);
    }

    /**
     * Loads the edit project page
     *
     * @param Project $project The ID of the project to edit
     *
     * @Template("TickitProjectBundle:Project:edit.html.twig")
     *
     * @ParamConverter("project", class="TickitProjectBundle:Project")
     *
     * @return array
     */
    public function editAction(Project $project)
    {
        $responseData = array('success' => false, 'errors' => array());

        $formType = $this->get('tickit_project.form.project');
        $form = $this->createForm($formType, $project);

        $form->submit($this->getRequest());

        if ($form->isValid()) {
            $project = $form->getData();

            $manager = $this->get('tickit_project.manager');
            $manager->update($project);

            $responseData['success'] = true;
            $responseData['returnUrl'] = $this->generateUrl('project_index');
        } else {
            $responseData['form'] = $this->render(
                'TickitProjectBundle:Project:edit.html.twig',
                array(
                    'form' => $form->createView()
                )
            )->getContent();
        }

        return new JsonResponse($responseData);
    }

    /**
     * Deletes a project from the application.
     *
     * @param Project $project The project to delete
     *
     * @ParamConverter("project", class="TickitProjectBundle:Project")
     *
     * @return RedirectResponse
     */
    public function deleteAction(Project $project)
    {
        $token = $this->getRequest()->query->get('token');
        $this->checkCsrfToken($token, static::CSRF_DELETE_INTENTION);

        $manager = $this->get('tickit_project.manager');
        $manager->delete($project);

        return new JsonResponse(array('success' => true));
    }
}
