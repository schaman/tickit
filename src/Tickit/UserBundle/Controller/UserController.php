<?php

/*
 * Tickit, an source web based bug management tool.
 * 
 * Copyright (C) 2013  Tickit Project <http://tickit.io>
 * 
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Tickit\UserBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Tickit\CoreBundle\Controller\Helper\BaseHelper;
use Tickit\CoreBundle\Controller\Helper\CsrfHelper;
use Tickit\CoreBundle\Controller\Helper\FormHelper;
use Tickit\UserBundle\Entity\User;
use Tickit\UserBundle\Form\Password\UserPasswordUpdater;
use Tickit\UserBundle\Manager\UserManager;

/**
 * Controller that provides actions to manipulate user entities
 *
 * @package Tickit\UserBundle\Controller
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class UserController
{
    /**
     * String constant containing the intention for CSRF delete action
     *
     * @const string
     */
    const CSRF_DELETE_INTENTION = 'delete_user';

    /**
     * The csrf controller helper
     *
     * @var CsrfHelper
     */
    protected $csrfHelper;

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
     * The user manager
     *
     * @var UserManager
     */
    protected $userManager;

    /**
     * The password updater
     *
     * @var UserPasswordUpdater
     */
    protected $passwordUpdater;

    /**
     * Constructor.
     *
     * @param CsrfHelper          $csrfHelper      The CSRF controller helper
     * @param FormHelper          $formHelper      The form controller helper
     * @param BaseHelper          $baseHelper      The base controller helper
     * @param UserManager         $userManager     The user manager
     * @param UserPasswordUpdater $passwordUpdater The password updater
     */
    public function __construct(
        CsrfHelper $csrfHelper,
        FormHelper $formHelper,
        BaseHelper $baseHelper,
        UserManager $userManager,
        UserPasswordUpdater $passwordUpdater
    ) {
        $this->csrfHelper = $csrfHelper;
        $this->formHelper = $formHelper;
        $this->baseHelper = $baseHelper;
        $this->userManager = $userManager;
        $this->passwordUpdater = $passwordUpdater;
    }

    /**
     * Loads the create user page
     *
     * @return JsonResponse
     */
    public function createAction()
    {
        $responseData = ['success' => false];
        $form = $this->formHelper->createForm('tickit_user', $this->userManager->createUser());

        $form->handleRequest($this->baseHelper->getRequest());
        if ($form->isValid()) {
            $this->userManager->create($form->getData());
            $responseData['success'] = true;
            $responseData['returnUrl'] = $this->baseHelper->generateUrl('user_index');
        } else {
            $response = $this->formHelper->renderForm('TickitUserBundle:User:create.html.twig', $form);
            $responseData['form'] = $response->getContent();
        }

        return new JsonResponse($responseData);
    }

    /**
     * Edit user action.
     *
     * Handles a request to update a user.
     *
     * @param User $existingUser The user to update
     *
     * @ParamConverter("user", class="TickitUserBundle:User")
     *
     * @return JsonResponse
     */
    public function editAction(User $existingUser)
    {
        $responseData = ['success' => false];
        $form = $this->formHelper->createForm('tickit_user', $existingUser);
        $form->handleRequest($this->baseHelper->getRequest());

        if ($form->isValid()) {
            $user = $this->passwordUpdater->updatePassword($existingUser, $form->getData());

            $this->userManager->update($user);
            $responseData['success'] = true;
        } else {
            $response = $this->formHelper->renderForm('TickitUserBundle:User:edit.html.twig', $form);
            $responseData['form'] = $response->getContent();
        }

        return new JsonResponse($responseData);
    }

    /**
     * Delete user action
     *
     * Handles a request to delete a user
     *
     * @param User $user The user to delete
     *
     * @ParamConverter("user", class="TickitUserBundle:User")
     *
     * @return JsonResponse
     */
    public function deleteAction(User $user)
    {
        $token = $this->baseHelper->getRequest()->query->get('token');
        $this->csrfHelper->checkCsrfToken($token, static::CSRF_DELETE_INTENTION);
        $this->userManager->deleteUser($user);

        return new JsonResponse(array('success' => true));
    }
}
