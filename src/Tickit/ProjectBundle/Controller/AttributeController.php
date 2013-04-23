<?php

namespace Tickit\ProjectBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
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
     * Lists all projects in the application
     *
     * @Template("TickitProjectBundle:Attribute:index.html.twig")
     *
     * @return array
     */
    public function indexAction()
    {
        $attributes = $this->get('tickit_project.attribute_manager')
                           ->getRepository()
                           ->findByFilters();

        $token = $this->get('form.csrf_provider')->generateCsrfToken('delete_project_attribute');

        return array('attributes' => $attributes, 'token' => $token);
    }

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
            $form->bind($this->getRequest());
            if ($form->isValid()) {
                $manager = $this->get('tickit_project.attribute_manager');
                $manager->create($form->getData());

                $generator = $this->get('tickit.flash_messages');
                $this->get('session')->getFlashBag()->add('notice', $generator->getEntityCreatedMessage('attribute'));
                $route = $this->generateUrl('project_attribute_index');

                return $this->redirect($route);
            }
        }

        return array('form' => $form->createView(), 'type' => $type);
    }

    /**
     * Edit attribute action.
     *
     * Displays relevant form for editing an existing attribute.
     *
     * @param string  $type The attribute type being edited
     * @param integer $id   The ID of the attribute being edited
     *
     * @Template("TickitProjectBundle:Attribute:edit.html.twig")
     *
     * @throws NotFoundHttpException
     *
     * @return array
     */
    public function editAction($type, $id)
    {
        $manager = $this->get('tickit_project.attribute_manager');
        $repo = $manager->getRepository();

        $attribute = $repo->find($id);

        if (empty($attribute)) {
            throw $this->createNotFoundException('Attribute not found');
        }

        if ($attribute->getType() !== $type) {
            throw $this->createNotFoundException('An invalid attribute type was specified');
        }

        $formType = $this->getFormTypeForAttributeType($type);
        $form = $this->createForm($formType, $attribute);

        if ('POST' == $this->getRequest()->getMethod()) {
            $form->bind($this->getRequest());
            if ($form->isValid()) {
                $manager->update($attribute);

                $generator = $this->get('tickit.flash_messages');
                $this->get('session')->getFlashBag()->add('notice', $generator->getEntityUpdatedMessage('attribute'));
            }
        }

        return array('attributeName' => $attribute->getName(), 'type' => $type, 'form' => $form->createView());
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

        $generator = $this->get('tickit.flash_messages');
        $this->get('session')->getFlashBag()->add('notice', $generator->getEntityDeletedMessage('attribute'));
        $route = $this->generateUrl('project_attribute_index');

        return $this->redirect($route);
    }

    /**
     * Controller helper for resolving form type instance for attributes.
     *
     * @param string $attributeType The attribute type
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
