<?php

namespace Tickit\UserBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Tickit\UserBundle\Entity\Group;
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

    /**
     * Serves content for the edit group page.
     *
     * @param integer $id The group ID to edit
     *
     * @throws NotFoundHttpException If no group is found for the given ID
     *
     * @Template("TickitUserBundle:Group:edit.html.twig")
     *
     * @return array
     */
    public function editAction($id)
    {
        $group = $this->get('tickit_user.group_manager')->find($id);

        if (!$group instanceof Group) {
            throw $this->createNotFoundException(sprintf('No group could be found for the given ID (%d)', $id));
        }

        // TODO: implement a GroupPermissions field type for these permissions
        $permissions = $this->get('tickit_permission.manager')->getUserPermissionData($group->getId());
        $group->setPermissions($permissions);

        $form = $this->createForm(new GroupFormType(), $group);

        if ('POST' === $this->getRequest()->getMethod()) {
            $form->submit($this->getRequest());

            if ($form->isValid()) {
                $group = $form->getData();

                $manager = $this->get('tickit_user.group_manager');
                $manager->update($group);

                $flash = $this->get('tickit.flash_messages');
                $flash->addEntityUpdatedMessage('group');

                $route = $this->get('router')->generate('group_index');

                return $this->redirect($route);
            }
        }

        return array('form' => $form->createView());
    }
}
