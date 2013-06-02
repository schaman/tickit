<?php

namespace Tickit\UserBundle\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Tickit\CoreBundle\Controller\AbstractCoreController;
use Tickit\UserBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Controller that provides actions to manipulate user entities
 *
 * @package Tickit\UserBundle\Controller
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class UserController extends AbstractCoreController
{
    /**
     * Lists all users in the system
     *
     * @Template("TickitUserBundle:User:index.html.twig")
     *
     * @return array
     */
    public function indexAction()
    {
        $users = $this->get('tickit_user.manager')
                      ->getRepository()
                      ->findUsers();

        return array('users' => $users);
    }

    /**
     * Loads the create user page
     *
     * @Template("TickitUserBundle:User:create.html.twig")
     *
     * @return array|RedirectResponse
     */
    public function createAction()
    {
        $user = new User();
        $form = $this->createForm('tickit_user', $user);

        if ('POST' == $this->getRequest()->getMethod()) {
            $form->submit($this->getRequest());
            if ($form->isValid()) {
                $user = $form->getData();
                $manager = $this->getUserManager();
                $manager->create($user);
                $router = $this->get('router');

                $flash = $this->get('tickit.flash_messages');
                $flash->addEntityCreatedMessage('user');

                return $this->redirect($router->generate('user_index'));
            }
        }

        return array('form' => $form->createView());
    }

    /**
     * Loads the edit user page
     *
     * @param integer $id The user ID to edit
     *
     * @Template("TickitUserBundle:User:edit.html.twig")
     *
     * @throws NotFoundHttpException If no user was found for the given ID
     *
     * @return array
     */
    public function editAction($id)
    {
        $existingUser = $this->get('tickit_user.manager')->find($id);
        $permissionManager = $this->get('tickit_permission.manager');

        if (empty($existingUser)) {
            throw $this->createNotFoundException('User not found');
        }

        $existingUserGroupId = $existingUser->getGroup()->getId();
        $permissions = $permissionManager->getUserPermissionData($existingUserGroupId, $existingUser->getId());
        $existingUser->setPermissions($permissions);
        $form = $this->createForm('tickit_user', $existingUser);

        $existingPassword = $existingUser->getPassword();

        if ('POST' === $this->getRequest()->getMethod()) {
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
                $user = $manager->create($user);
                $form = $this->createForm('tickit_user', $user);

                $flash = $this->get('tickit.flash_messages');
                $flash->addEntityUpdatedMessage('user');
            }
        }

        return array('form' => $form->createView());
    }
}
