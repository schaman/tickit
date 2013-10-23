<?php

namespace Tickit\ProjectBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Tickit\CoreBundle\Controller\Helper\BaseHelper;
use Tickit\CoreBundle\Controller\Helper\CsrfHelper;
use Tickit\CoreBundle\Controller\Helper\FormHelper;
use Tickit\ProjectBundle\Entity\AbstractAttribute;
use Tickit\ProjectBundle\Form\Guesser\AttributeFormTypeGuesser;
use Tickit\ProjectBundle\Manager\AttributeManager;

/**
 * Project attribute controller.
 *
 * Responsible for handling requests related to project attributes
 *
 * @package Tickit\ProjectBundle\Controller
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class AttributeController
{
    /**
     * String intention for deleting a project attribute
     *
     * @const string
     */
    const CSRF_DELETE_INTENTION = 'delete_project_attribute';

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
     * The attribute form type guesser
     *
     * @var AttributeFormTypeGuesser
     */
    protected $formTypeGuesser;

    /**
     * The attribute manager
     *
     * @var AttributeManager
     */
    protected $attributeManager;

    /**
     * The CSRF controller helper
     *
     * @var CsrfHelper
     */
    protected $csrfHelper;

    /**
     * Constructor.
     *
     * @param FormHelper               $formHelper       The form controller helper
     * @param BaseHelper               $baseHelper       The base controller helper
     * @param AttributeFormTypeGuesser $formTypeGuesser  The attribute form type guesser
     * @param AttributeManager         $attributeManager The attribute manager
     * @param CsrfHelper               $csrfHelper       The CSRF form controller helper
     */
    public function __construct(
        FormHelper $formHelper,
        BaseHelper $baseHelper,
        AttributeFormTypeGuesser $formTypeGuesser,
        AttributeManager $attributeManager,
        CsrfHelper $csrfHelper
    ) {
        $this->formHelper = $formHelper;
        $this->baseHelper = $baseHelper;
        $this->formTypeGuesser = $formTypeGuesser;
        $this->attributeManager = $attributeManager;
        $this->csrfHelper = $csrfHelper;
    }

    /**
     * Create attribute action.
     *
     * Handles a request to create an attribute
     *
     * @param string $type The type of the attribute to create
     *
     * @throws NotFoundHttpException If the given type is not valid
     *
     * @return JsonResponse
     */
    public function createAction($type)
    {
        try {
            $attribute = AbstractAttribute::factory($type);
        } catch (\InvalidArgumentException $e) {
            throw new NotFoundHttpException('An invalid attribute type was specified');
        }

        $responseData = ['success' => false];
        $formType = $this->formTypeGuesser->guessByAttributeType($type);
        $form = $this->formHelper->createForm($formType, $attribute);
        $form->handleRequest($this->baseHelper->getRequest());
        if ($form->isValid()) {
            $this->attributeManager->create($form->getData());
            $responseData['success'] = true;
            $responseData['returnUrl'] = $this->baseHelper->generateUrl('project_attribute_index');
        } else {
            $params = ['type' => $attribute->getType()];
            $response = $this->formHelper->renderForm('TickitProjectBundle:Attribute:create.html.twig', $form, $params);
            $responseData['form'] = $response->getContent();
        }

        return new JsonResponse($responseData);
    }

    /**
     * Edit attribute action.
     *
     * Handles a request to update an attribute.
     *
     * @ParamConverter("attribute", class="TickitProjectBundle:AbstractAttribute")
     *
     * @param AbstractAttribute $attribute The attribute that is being edited
     *
     * @return JsonResponse
     */
    public function editAction(AbstractAttribute $attribute)
    {
        $responseData = ['success' => false];
        $formType = $this->formTypeGuesser->guessByAttributeType($attribute->getType());

        $form = $this->formHelper->createForm($formType, $attribute);
        $form->handleRequest($this->baseHelper->getRequest());
        if ($form->isValid()) {
            $this->attributeManager->update($attribute);
            $responseData['success'] = true;
        } else {
            $params = ['type' => $attribute->getType()];
            $response = $this->formHelper->renderForm('TickitProjectBundle:Attribute:edit.html.twig', $form, $params);
            $responseData['form'] = $response->getContent();
        }

        return new JsonResponse($responseData);
    }

    /**
     * Deletes an attribute from the application
     *
     * @param AbstractAttribute $attribute The attribute to delete
     *
     * @ParamConverter("attribute", class="TickitProjectBundle:AbstractAttribute")
     *
     * @return JsonResponse
     */
    public function deleteAction(AbstractAttribute $attribute)
    {
        $token = $this->baseHelper->getRequest()->query->get('token');
        $this->csrfHelper->checkCsrfToken($token, static::CSRF_DELETE_INTENTION);
        $this->attributeManager->delete($attribute);

        return new JsonResponse(['success' => true]);
    }
}
