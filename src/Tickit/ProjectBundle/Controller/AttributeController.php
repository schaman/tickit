<?php

namespace Tickit\ProjectBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Tickit\CoreBundle\Controller\AbstractCoreController;
use Tickit\ProjectBundle\Entity\Attribute;
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
     * Add attribute action.
     *
     * Displays relevant form for adding a project attribute of the given type.
     *
     * @param string $type The type of the attribute to add
     *
     * @Template("TickitProjectBundle:Attribute:add.html.twig")
     *
     * @throws NotFoundHttpException If the given type is not valid
     *
     * @return array
     */
    public function addAction($type)
    {
        $availableTypes = Attribute::getAvailableTypes();

        if (!in_array($type, $availableTypes)) {
            throw $this->createNotFoundException('An invalid attribute type was specified');
        }

        switch ($type) {
            case Attribute::TYPE_CHOICE:
                break;
            case Attribute::TYPE_ENTITY:
                break;
            case Attribute::TYPE_LITERAL:
                $formType = new LiteralAttributeFormType();
                break;
        }

        $attribute = new Attribute();
        $form = $this->createForm($formType, $attribute);

        if ('POST' == $this->getRequest()->getMethod()) {
            $form->bind($this->getRequest());
            if ($form->isValid()) {
                $manager = $this->get('tickit_project.attribute_manager');
                $manager->create($form->getData());
            }
        }

        return array('form' => $form->createView(), 'type' => $type);
    }
}
