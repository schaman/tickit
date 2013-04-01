<?php

namespace Tickit\UserBundle\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Tickit\CoreBundle\Controller\AbstractCoreController;
use Tickit\UserBundle\Entity\User;
use Tickit\UserBundle\Form\Type\UserFormType;
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
     * Loads the add user page
     *
     * @Template("TickitUserBundle:User:add.html.twig")
     *
     * @return array|RedirectResponse
     */
    public function addAction()
    {
        $user = new User();
        $user->setEnabled(true);

        $formType = new UserFormType();
        $form = $this->createForm($formType);
        $form->setData($user);

        if ('POST' == $this->getRequest()->getMethod()) {
            $form->bind($this->getRequest());
            if ($form->isValid()) {
                $user = $form->getData();
                $manager = $this->getUserManager();
                $manager->create($user);
                $router = $this->get('router');

                $generator = $this->get('tickit.flash_messages');
                $this->get('session')->getFlashbag()->add('notice', $generator->getEntityCreatedMessage('user'));

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
        $user = $this->get('tickit_user.manager')
                     ->getRepository()
                     ->findOneById($id);

        if (empty($user)) {
            throw $this->createNotFoundException('User not found');
        }

        $formType = new UserFormType();
        $form = $this->createForm($formType, $user);

        if ('POST' === $this->getRequest()->getMethod()) {
            $form->bind($this->getRequest());
            if ($form->isValid()) {
                $user = $form->getData();
                $manager = $this->get('tickit_user.manager');
                $manager->create($user);

                $generator = $this->get('tickit.flash_messages');
                $this->get('session')->getFlashbag()->add('notice', $generator->getEntityUpdatedMessage('user'));
            }
        }

        return array('form' => $form->createView());
    }
}
