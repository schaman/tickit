<?php

namespace Tickit\UserBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
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
     * Handles a request to create a group.
     *
     * @return JsonResponse
     */
    public function createAction()
    {
        $responseData = ['success' => false];
        $group = new Group('');
        $permissions = $this->get('tickit_permission.manager')->getPermissionModels();
        $group->setPermissions($permissions);

        $form = $this->createForm(new GroupFormType(), $group);
        $form->submit($this->getRequest());

        if ($form->isValid()) {
            $group = $form->getData();
            $this->get('tickit_user.group_manager')->create($group);
            $route = $this->get('router')->generate('group_index');
            $responseData['success'] = true;
            $responseData['returnUrl'] = $route;
        } else {
            $responseData['form'] = $this->render(
                'TickitUserBundle:Group:create.html.twig',
                ['form' => $form->createView()]
            );
        }

        return new JsonResponse($responseData);
    }

    /**
     * Handles a request to update a group.
     *
     * @param Group $group The group to edit
     *
     * @throws NotFoundHttpException If no group is found for the given ID
     *
     * @ParamConverter("group", class="TickitUserBundle:Group")
     *
     * @return JsonResponse
     */
    public function editAction(Group $group)
    {
        $responseData = ['success' => false];
        $permissions = $this->get('tickit_permission.manager')->getUserPermissionData($group->getId());
        $group->setPermissions($permissions);

        $form = $this->createForm(new GroupFormType(), $group);
        $form->submit($this->getRequest());

        if ($form->isValid()) {
            $group = $form->getData();
            $this->get('tickit_user.group_manager')->update($group);
            $route = $this->get('router')->generate('group_index');
            $responseData['success'] = true;
            $responseData['returnUrl'] = $route;
        } else {
            $responseData['form'] = $this->render(
                'TickitUserBundle:Group:edit.html.twig',
                ['form' => $form->createView()]
            )->getContent();
        }

        return new JsonResponse($responseData);
    }
}
