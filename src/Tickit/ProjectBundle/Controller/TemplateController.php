<?php

namespace Tickit\ProjectBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Tickit\ProjectBundle\Entity\Project;

/**
 * Template controller.
 *
 * Serves template content for the bundle.
 *
 * @package Tickit\ProjectBundle\Controller
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class TemplateController extends Controller
{
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

        $attributes = $this->get('tickit_project.attribute_manager')->getAttributeValuesForProject($project);
        $project->setAttributes($attributes);
        $form = $this->createForm($this->get('tickit_project.form.project'), $project);

        return $this->render('TickitProjectBundle:Project:create.html.twig', array('form' => $form->createView()));
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
        $form = $this->createForm($this->get('tickit_project.form.project'), $project);

        return $this->render('TickitProjectBundle:Project:edit.html.twig', array('form' => $form->createView()));
    }
}
