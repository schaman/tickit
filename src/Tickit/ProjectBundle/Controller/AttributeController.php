<?php

namespace Tickit\ProjectBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Tickit\CoreBundle\Controller\AbstractCoreController;
use Tickit\ProjectBundle\Entity\AbstractAttribute;
use Tickit\ProjectBundle\Entity\ChoiceAttribute;
use Tickit\ProjectBundle\Entity\EntityAttribute;
use Tickit\ProjectBundle\Entity\LiteralAttribute;
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
        $availableTypes = AbstractAttribute::getAvailableTypes();

        if (!in_array($type, $availableTypes)) {
            throw $this->createNotFoundException('An invalid attribute type was specified');
        }

        switch ($type) {
            case AbstractAttribute::TYPE_CHOICE:
                $attribute = new ChoiceAttribute();
                break;
            case AbstractAttribute::TYPE_ENTITY:
                $attribute = new EntityAttribute();
                break;
            default:
                $attribute = new LiteralAttribute();
                $formType = new LiteralAttributeFormType();
        }

        $form = $this->createForm($formType, $attribute);

        if ('POST' == $this->getRequest()->getMethod()) {
            $form->bind($this->getRequest());
            if ($form->isValid()) {
                $manager = $this->get('tickit_project.attribute_manager');
                $manager->create($form->getData());

                $generator = $this->get('tickit.flash_messages');
                $this->get('session')->getFlashBag()->add('notice', $generator->getEntityCreatedMessage('literal attribute'));

                $route = $this->generateUrl('project_attributes_index');
                return $this->redirect($route);
            }
        }

        return array('form' => $form->createView(), 'type' => $type);
    }
}
