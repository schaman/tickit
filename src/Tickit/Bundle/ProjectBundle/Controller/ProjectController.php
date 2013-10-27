<?php

/*
 * Tickit, an open source web based bug management tool.
 * 
 * Copyright (C) 2013  Tickit Project <http://tickit.io>
 * 
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Tickit\ProjectBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Tickit\Bundle\CoreBundle\Controller\Helper\BaseHelper;
use Tickit\Bundle\CoreBundle\Controller\Helper\CsrfHelper;
use Tickit\Bundle\CoreBundle\Controller\Helper\FormHelper;
use Tickit\ProjectBundle\Entity\Project;
use Tickit\ProjectBundle\Form\Type\ProjectFormType;
use Tickit\ProjectBundle\Manager\AttributeManager;
use Tickit\ProjectBundle\Manager\ProjectManager;

/**
 * Project controller.
 *
 * Responsible for handling requests related to projects
 *
 * @package Tickit\ProjectBundle\Controller
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class ProjectController
{
    /**
     * String intention for deleting a project
     *
     * @const string
     */
    const CSRF_DELETE_INTENTION = 'delete_project';

    /**
     * The form controller helper
     *
     * @var FormHelper
     */
    protected $formHelper;

    /**
     * The base controller helper
     *
     * @var BaseHelper
     */
    protected $baseHelper;

    /**
     * The attribute manager
     *
     * @var AttributeManager
     */
    protected $attributeManager;

    /**
     * The project manager
     *
     * @var ProjectManager
     */
    protected $projectManager;

    /**
     * The project form type
     *
     * @var ProjectFormType
     */
    protected $projectFormType;

    /**
     * The CSRF controller helper
     *
     * @var CsrfHelper
     */
    protected $csrfHelper;

    /**
     * Constructor.
     *
     * @param FormHelper       $formHelper       The form controller helper
     * @param BaseHelper       $baseHelper       The base controller helper
     * @param AttributeManager $attributeManager The attribute manager
     * @param ProjectManager   $projectManager   The project manager
     * @param ProjectFormType  $projectFormType  The project form type
     * @param CsrfHelper       $csrfHelper       The CSRF controller helper
     */
    public function __construct(
        FormHelper $formHelper,
        BaseHelper $baseHelper,
        AttributeManager $attributeManager,
        ProjectManager $projectManager,
        ProjectFormType $projectFormType,
        CsrfHelper $csrfHelper
    ) {
        $this->formHelper = $formHelper;
        $this->baseHelper = $baseHelper;
        $this->attributeManager = $attributeManager;
        $this->projectManager = $projectManager;
        $this->projectFormType = $projectFormType;
        $this->csrfHelper = $csrfHelper;
    }

    /**
     * Create action.
     *
     * Serves a JsonResponse containing the form markup template
     *
     * @return JsonResponse
     */
    public function createAction()
    {
        $responseData = ['success' => false];
        $project = new Project();
        $attributes = $this->attributeManager->getAttributeValuesForProject($project);
        $project->setAttributes($attributes);

        $form = $this->formHelper->createForm($this->projectFormType, $project);
        $form->handleRequest($this->baseHelper->getRequest());

        if ($form->isValid()) {
            $this->projectManager->create($form->getData());
            $responseData['success'] = true;
            $responseData['returnUrl'] = $this->baseHelper->generateUrl('project_index');
        } else {
            $response = $this->formHelper->renderForm('TickitProjectBundle:Project:create.html.twig', $form);
            $responseData['form'] = $response->getContent();
        }

        return new JsonResponse($responseData);
    }

    /**
     * Loads the edit project page
     *
     * @param Project $project The ID of the project to edit
     *
     * @ParamConverter("project", class="TickitProjectBundle:Project")
     *
     * @return JsonResponse
     */
    public function editAction(Project $project)
    {
        $responseData = ['success' => false];
        $form = $this->formHelper->createForm($this->projectFormType, $project);
        $form->handleRequest($this->baseHelper->getRequest());

        if ($form->isValid()) {
            $this->projectManager->update($form->getData());
            $responseData['success'] = true;
        } else {
            $response = $this->formHelper->renderForm('TickitProjectBundle:Project:edit.html.twig', $form);
            $responseData['form'] = $response->getContent();
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
        $token = $this->baseHelper->getRequest()->query->get('token');
        $this->csrfHelper->checkCsrfToken($token, static::CSRF_DELETE_INTENTION);
        $this->projectManager->delete($project);

        return new JsonResponse(['success' => true]);
    }
}
