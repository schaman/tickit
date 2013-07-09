<?php

namespace Tickit\ProjectBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Tickit\ProjectBundle\Entity\AbstractAttribute;
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
            throw $this->createNotFoundException('An invalid attribute type was specified');
        }

        $formType = $this->get('tickit_project.attribute_form_type_guesser')
                         ->guessByAttributeType($attribute->getType());

        $form = $this->createForm($formType, $attribute);

        return $this->render(
            'TickitProjectBundle:Attribute:create.html.twig',
            array('form' => $form->createView(), 'type' => $type)
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
     */
    public function editProjectAttributeFormAction(AbstractAttribute $attribute)
    {
        $formType = $this->get('tickit_project.attribute_form_type_guesser')
                         ->guessByAttributeType($attribute->getType());

        $form = $this->createForm($formType, $attribute);

        return $this->render(
            'TickitProjectBundle:Attribute:edit.html.twig',
            array('form' => $form->createView(), 'type' => $attribute->getType(), 'attributeName' => $attribute->getName())
        );
    }
}
