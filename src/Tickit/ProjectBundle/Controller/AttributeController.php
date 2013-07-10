<?php

namespace Tickit\ProjectBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Tickit\CoreBundle\Controller\AbstractCoreController;
use Tickit\ProjectBundle\Entity\AbstractAttribute;
use Tickit\ProjectBundle\Form\Type\AbstractAttributeFormType;
use Tickit\ProjectBundle\Form\Type\ChoiceAttributeFormType;
use Tickit\ProjectBundle\Form\Type\LiteralAttributeFormType;

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
     * Create attribute action.
     *
     * Displays relevant form for creating a project attribute of the given type.
     *
     * @param string $type The type of the attribute to add
     *
     * @Template("TickitProjectBundle:Attribute:create.html.twig")
     *
     * @throws NotFoundHttpException If the given type is not valid
     *
     * @return array
     */
    public function createAction($type)
    {
        try {
            $attribute = AbstractAttribute::factory($type);
        } catch (\InvalidArgumentException $e) {
            throw $this->createNotFoundException('An invalid attribute type was specified');
        }

        $formType = $this->getFormTypeForAttributeType($type);
        $form = $this->createForm($formType, $attribute);

        if ('POST' == $this->getRequest()->getMethod()) {
            $form->submit($this->getRequest());
            if ($form->isValid()) {
                $manager = $this->get('tickit_project.attribute_manager');
                $manager->create($form->getData());

                $flash = $this->get('tickit.flash_messages');
                $flash->addEntityCreatedMessage('attribute');
                $route = $this->generateUrl('project_attribute_index');

                return $this->redirect($route);
            }
        }

        return array('form' => $form->createView(), 'type' => $type);
    }

    /**
     * Edit attribute action.
     *
     * Handles a request to update an attribute.
     *
     * @param AbstractAttribute $attribute The attribute that is being edited
     *
     * @Template("TickitProjectBundle:Attribute:edit.html.twig")
     *
     * @throws NotFoundHttpException
     *
     * @return array
     */
    public function editAction(AbstractAttribute $attribute)
    {
        $responseData = array('success' => false);

        $formType = $this->get('tickit_project.attribute_form_type_guesser')
                         ->guessByAttributeType($attribute->getType());

        $form = $this->createForm($formType, $attribute);
        $form->submit($this->getRequest());
        if ($form->isValid()) {
            $this->get('tickit_project.attribute_manager')
                 ->update($attribute);

            $responseData['success'] = true;
        } else {
            $responseData['form'] = $this->render(
                'TickitProject:Attribute:edit.html.twig',
                array(
                    'form' => $form->createView(),
                    'type' => $attribute->getType()
                )
            )->getContent();
        }

        return new JsonResponse($responseData);
    }

    /**
     * Deletes an attribute from the application
     *
     * @param integer $id The ID of the attribute to delete
     *
     * @throws NotFoundHttpException If no attribute was found for the given ID or an invalid CSRF token is provided
     *
     * @return RedirectResponse
     */
    public function deleteAction($id)
    {
        $token = $this->getRequest()->query->get('token');
        $this->checkCsrfToken($token, 'delete_project_attribute');

        $manager = $this->get('tickit_project.attribute_manager');
        $attribute = $manager->getRepository()->find($id);

        if (empty($attribute)) {
            throw $this->createNotFoundException('Attribute not found');
        }

        $manager->delete($attribute);

        $flash = $this->get('tickit.flash_messages');
        $flash->addEntityDeletedMessage('attribute');

        $route = $this->generateUrl('project_attribute_index');

        return $this->redirect($route);
    }

    /**
     * Controller helper for resolving form type instance for attributes.
     *
     * @param string $attributeType The attribute type
     *
     * @deprecated Use the AttributeFormTypeGuesser instead
     *
     * @return AbstractAttributeFormType
     */
    protected function getFormTypeForAttributeType($attributeType)
    {
        switch ($attributeType) {
            case AbstractAttribute::TYPE_CHOICE:
                $formType = new ChoiceAttributeFormType();
                break;
            case AbstractAttribute::TYPE_ENTITY:
                $formType = $this->get('tickit_project.form.entity_attribute');
                break;
            default:
                $formType = new LiteralAttributeFormType();
        }

        return $formType;
    }
}
