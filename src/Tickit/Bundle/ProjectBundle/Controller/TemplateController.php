<?php

/*
 * Tickit, an open source web based bug management tool.
 *
 * Copyright (C) 2014  Tickit Project <http://tickit.io>
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

namespace Tickit\Bundle\ProjectBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Tickit\Component\Controller\Helper\FormHelper;
use Tickit\Component\Model\Project\AbstractAttribute;
use Tickit\Component\Model\Project\Project;
use Tickit\Bundle\ProjectBundle\Form\Guesser\AttributeFormTypeGuesser;
use Tickit\Component\Entity\Manager\Project\AttributeManager;
use Tickit\Component\Entity\Manager\UserManager;

/**
 * Template controller.
 *
 * Serves template content for the bundle.
 *
 * @package Tickit\Bundle\ProjectBundle\Controller
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class TemplateController
{
    /**
     * The user manager
     *
     * @var AttributeManager
     */
    protected $attributeManager;

    /**
     * The form helper
     *
     * @var FormHelper
     */
    protected $formHelper;

    /**
     * Attribute form type guesser
     *
     * @var AttributeFormTypeGuesser
     */
    protected $attributeFormTypeGuesser;

    /**
     * Constructor.
     *
     * @param AttributeManager         $attributeManager         The user manager
     * @param FormHelper               $formHelper               A form factory
     * @param AttributeFormTypeGuesser $attributeFormTypeGuesser The attribute form type guesser
     */
    public function __construct(
        AttributeManager $attributeManager,
        FormHelper $formHelper,
        AttributeFormTypeGuesser $attributeFormTypeGuesser
    ) {
        $this->attributeManager = $attributeManager;
        $this->formHelper = $formHelper;
        $this->attributeFormTypeGuesser = $attributeFormTypeGuesser;
    }

    /**
     * Create project form action.
     *
     * Serves a template for the project create form.
     *
     * @return Response
     */
    public function createProjectFormAction()
    {
        $project = new Project();

        $attributes = $this->attributeManager->getAttributeValuesForProject($project);
        $project->setAttributes($attributes);
        $form = $this->formHelper->createForm('tickit_project', $project);

        return $this->formHelper->renderForm('TickitProjectBundle:Project:create.html.twig', $form);
    }

    /**
     * Edit project form action.
     *
     * Serves a template for the project edit form.
     *
     * @param Project $project The project that is being edited
     *
     * @ParamConverter("project", class="TickitProjectBundle:Project")
     *
     * @return Response
     */
    public function editProjectFormAction(Project $project)
    {
        $form = $this->formHelper->createForm('tickit_project', $project);

        return $this->formHelper->renderForm('TickitProjectBundle:Project:edit.html.twig', $form);
    }

    /**
     * Create project attribute form action.
     *
     * Serves a template for the project attribute form.
     *
     * @param string $type The type of the attribute to add
     *
     * @throws NotFoundHttpException If the attribute type is invalid
     *
     * @return Response
     */
    public function createProjectAttributeFormAction($type)
    {
        try {
            $attribute = AbstractAttribute::factory($type);
        } catch (\InvalidArgumentException $e) {
            throw new NotFoundHttpException('An invalid attribute type was specified');
        }

        $formType = $this->attributeFormTypeGuesser->guessByAttributeType($attribute->getType());
        $form = $this->formHelper->createForm($formType, $attribute);

        return $this->formHelper->renderForm(
            'TickitProjectBundle:Attribute:create.html.twig',
            $form,
            array('type' => $type)
        );
    }

    /**
     * Edit project attribute form action.
     *
     * Serves a template for the edit project attribute form.
     *
     * @param AbstractAttribute $attribute The attribute to serve the edit form for
     *
     * @ParamConverter("attribute", class="TickitProjectBundle:AbstractAttribute")
     *
     * @return Response
     */
    public function editProjectAttributeFormAction(AbstractAttribute $attribute)
    {
        $formType = $this->attributeFormTypeGuesser->guessByAttributeType($attribute->getType());
        $form = $this->formHelper->createForm($formType, $attribute);

        return $this->formHelper->renderForm(
            'TickitProjectBundle:Attribute:edit.html.twig',
            $form,
            array('type' => $attribute->getType())
        );
    }

    /**
     * Filter form action.
     *
     * Serves a template containing a filter form for projects.
     *
     * @return Response
     */
    public function filterFormAction()
    {
        $form = $this->formHelper->createForm('tickit_project_filters', []);

        return $this->formHelper->renderForm(
            'TickitProjectBundle:Filters:filter-form.html.twig',
            $form
        );
    }
}
