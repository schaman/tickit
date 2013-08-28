<?php

namespace Tickit\UserBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Tickit\CoreBundle\Controller\AbstractCoreController;
use Tickit\UserBundle\Entity\User;

/**
 * Controller that provides actions to manipulate user entities
 *
 * @package Tickit\UserBundle\Controller
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class UserController extends AbstractCoreController
{
    /**
     * String constant containing the intention for CSRF delete action
     *
     * @const string
     */
    const CSRF_DELETE_INTENTION = 'delete_user';

    /**
     * Loads the create user page
     *
     * @return JsonResponse
     */
    public function createAction()
    {
        $responseData = ['success' => false];
        $manager = $this->get('tickit_user.manager');
        $form = $this->createForm('tickit_user', $manager->createUser());

        $form->submit($this->getRequest());
        if ($form->isValid()) {
            $manager->create($form->getData());
            $responseData['success'] = true;
            $responseData['returnUrl'] = $this->get('router')->generate('user_index');
        } else {
            $responseData['form'] = $this->renderForm('TickitUserBundle:User:create.html.twig', $form);
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
        $permissionManager = $this->get('tickit_permission.manager');
        $existingUserGroupId = $existingUser->getGroup()->getId();
        $permissions = $permissionManager->getUserPermissionData($existingUserGroupId, $existingUser->getId());
        $existingUser->setPermissions($permissions);
        $form = $this->createForm('tickit_user', $existingUser);
        $existingPassword = $existingUser->getPassword();
        $form->submit($this->getRequest());

        if ($form->isValid()) {
            /** @var User $user */
            $user = $form->getData();

            // we restore the password if no new one was provided on the form so that the user's
            // password isn't set to a blank string in the database
            if ($user->getPassword() === null) {
                $user->setPassword($existingPassword);
            } else {
                // set the plain password on the user from the one that was provided in the form
                $user->setPlainPassword($user->getPassword());
            }

            $manager = $this->get('tickit_user.manager');
            $manager->update($user);
            $responseData['success'] = true;
        } else {
            $responseData['form'] = $this->renderForm('TickitUserBundle:User:edit.html.twig', $form);
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
     * @throws NotFoundHttpException If the CSRF token is invalid
     *
     * @return JsonResponse
     */
    public function deleteAction(User $user)
    {
        $token = $this->getRequest()->query->get('token');
        $tokenProvider = $this->get('form.csrf_provider');

        if (!$tokenProvider->isCsrfTokenValid(static::CSRF_DELETE_INTENTION, $token)) {
            throw $this->createNotFoundException('Invalid CSRF token');
        }

        $this->get('tickit_user.manager')->deleteUser($user);

        return new JsonResponse(array('success' => true));
    }
}
