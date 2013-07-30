<?php

namespace Tickit\ProjectBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Tickit\CoreBundle\Controller\AbstractCoreController;
use Tickit\ProjectBundle\Entity\AbstractAttribute;

/**
 * Project attribute controller.
 *
 * Responsible for handling requests related to project attributes
 *
 * @package Tickit\ProjectBundle\Controller
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class AttributeController extends AbstractCoreController
{
    /**
     * String intention for deleting a project attribute
     *
     * @const string
     */
    const CSRF_DELETE_INTENTION = 'delete_project_attribute';

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
            throw $this->createNotFoundException('An invalid attribute type was specified');
        }

        $responseData = ['success' => false];
        $formType = $this->get('tickit_project.attribute_form_type_guesser')
                         ->guessByAttributeType($type);

        $form = $this->createForm($formType, $attribute);
        $form->submit($this->getRequest());
        if ($form->isValid()) {
            $this->get('tickit_project.attribute_manager')->create($form->getData());
            $responseData['success'] = true;
            $responseData['returnUrl'] = $this->generateUrl('project_attribute_index');
        } else {
            $responseData['form'] = $this->render(
                'TickitProjectBundle:Attribute:create.html.twig',
                ['form' => $form->createView(), 'type' => $attribute->getType()]
            )->getContent();
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
        $formType = $this->get('tickit_project.attribute_form_type_guesser')
                         ->guessByAttributeType($attribute->getType());

        $form = $this->createForm($formType, $attribute);
        $form->submit($this->getRequest());
        if ($form->isValid()) {
            $this->get('tickit_project.attribute_manager')->update($attribute);
            $responseData['success'] = true;
        } else {
            $responseData['form'] = $this->render(
                'TickitProjectBundle:Attribute:edit.html.twig',
                ['form' => $form->createView(), 'type' => $attribute->getType()]
            )->getContent();
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
        $token = $this->getRequest()->query->get('token');
        $this->checkCsrfToken($token, static::CSRF_DELETE_INTENTION);

        $manager = $this->get('tickit_project.attribute_manager');
        $manager->delete($attribute);

        return new JsonResponse(array('success' => true));
    }
}
