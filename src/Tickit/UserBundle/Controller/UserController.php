<?php

namespace Tickit\UserBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Tickit\CoreBundle\Controller\CoreController;
use Tickit\UserBundle\Form\Type\EditFormType;

/**
 * Controller that provides actions to manipulate user entities
 *
 * @package Tickit\UserBundle\Controller
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class UserController extends CoreController
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
        $users = $this->getDoctrine()
                      ->getManager()
                      ->getRepository('TickitUserBundle:User')
                      ->findUsers();

        return array('users' => $users);
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
        $user = $this->getDoctrine()
                     ->getManager()
                     ->getRepository('TickitUserBundle:User')
                     ->findOneById($id);

        if (empty($user)) {
            throw $this->createNotFoundException('User not found');
        }

        $formType = new EditFormType($user);
        $form = $this->createForm($formType, $user);

        return array('form' => $form->createView());
    }
}
