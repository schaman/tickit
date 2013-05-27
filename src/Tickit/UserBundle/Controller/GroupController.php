<?php

namespace Tickit\UserBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Tickit\UserBundle\Form\Type\GroupFormType;

/**
 * Groups controller.
 *
 * Provides functionality for interacting with group related data in the
 * application.
 *
 * @package Tickit\UserBundle\Controller
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class GroupController extends Controller
{
    /**
     * Lists all groups in the application.
     *
     * @Template("TickitUserBundle:Group:index.html.twig")
     *
     * @return array
     */
    public function indexAction()
    {
        $groups = $this->get('doctrine')
                       ->getRepository('TickitUserBundle:Group')
                       ->findGroups();

        return array('groups' => $groups);
    }

    /**
     * Serves content for the create group page.
     *
     * @Template("TickitUserBundle:Group:create.html.twig")
     *
     * @return array
     */
    public function createAction()
    {
        $form = $this->createForm(new GroupFormType());

        if ('POST' === $this->getRequest()->getMethod()) {
            $form->submit($this->getRequest());

            if ($form->isValid()) {
                $group = $form->getData();

                $manager = $this->get('tickit_user.group_manager');
                $manager->create($group);

                $flash = $this->get('tickit.flash_messages');
                $flash->addEntityCreatedMessage('group');

                $route = $this->get('router')->generate('group_index');

                return $this->redirect($route);
            }
        }

        return array('form' => $form->createView());
    }
}
