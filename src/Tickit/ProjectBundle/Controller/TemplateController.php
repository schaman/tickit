<?php

namespace Tickit\ProjectBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Tickit\ProjectBundle\Entity\AbstractAttribute;
use Tickit\ProjectBundle\Entity\Project;
use Tickit\ProjectBundle\Form\Guesser\AttributeFormTypeGuesser;
use Tickit\ProjectBundle\Form\Type\ProjectFormType;
use Tickit\ProjectBundle\Manager\AttributeManager;
use Tickit\UserBundle\Manager\UserManager;

/**
 * Template controller.
 *
 * Serves template content for the bundle.
 *
 * @package Tickit\ProjectBundle\Controller
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class TemplateController
{
    /**
     * The user manager
     *
     * @var UserManager
     */
    protected $attributeManager;

    /**
     * A form factory
     *
     * @var FormFactoryInterface
     */
    protected $formFactory;

    /**
     * A template engine
     *
     * @var EngineInterface
     */
    protected $templateEngine;

    /**
     * The project form type
     *
     * @var ProjectFormType
     */
    protected $projectFormType;

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
     * @param FormFactoryInterface     $formFactory              A form factory
     * @param EngineInterface          $templateEngine           A template engine
     * @param ProjectFormType          $projectFormType          The project form type
     * @param AttributeFormTypeGuesser $attributeFormTypeGuesser The attribute form type guesser
     */
    public function __construct(
        AttributeManager $attributeManager,
        FormFactoryInterface $formFactory,
        EngineInterface $templateEngine,
        ProjectFormType $projectFormType,
        AttributeFormTypeGuesser $attributeFormTypeGuesser
    ) {
        $this->attributeManager = $attributeManager;
        $this->formFactory = $formFactory;
        $this->templateEngine = $templateEngine;
        $this->projectFormType = $projectFormType;
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
        $form = $this->formFactory->create($this->projectFormType, $project);

        return $this->templateEngine->renderResponse(
            'TickitProjectBundle:Project:create.html.twig',
            array('form' => $form->createView())
        );
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
        $form = $this->formFactory->create($this->projectFormType, $project);

        return $this->templateEngine->renderResponse(
            'TickitProjectBundle:Project:edit.html.twig',
            array('form' => $form->createView())
        );
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
        $form = $this->formFactory->create($formType, $attribute);

        return $this->templateEngine->renderResponse(
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
     *
     * @return Response
     */
    public function editProjectAttributeFormAction(AbstractAttribute $attribute)
    {
        $formType = $this->attributeFormTypeGuesser->guessByAttributeType($attribute->getType());

        $form = $this->formFactory->create($formType, $attribute);

        return $this->templateEngine->renderResponse(
            'TickitProjectBundle:Attribute:edit.html.twig',
            array('form' => $form->createView(), 'type' => $attribute->getType())
        );
    }
}
