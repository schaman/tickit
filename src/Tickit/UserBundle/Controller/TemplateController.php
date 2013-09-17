<?php

namespace Tickit\UserBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Tickit\UserBundle\Entity\User;

/**
 * Template controller.
 *
 * Serves dynamic templates for the user bundle.
 *
 * @package Tickit\UserBundle\Controller
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class TemplateController extends Controller
{
    /**
     * Create user form action.
     *
     * Serves the form markup for creating a user.
     *
     * @return Response
     */
    public function createUserFormAction()
    {
        $user = $this->get('tickit_user.manager')->createUser();
        $form = $this->createForm('tickit_user', $user);

        return $this->render('TickitUserBundle:User:create.html.twig', array('form' => $form->createView()));
    }

    /**
     * Edit user form action.
     *
     * Serves the form markup for editing a user
     *
     * @param User $user The user that is being edited
     *
     * @ParamConverter("user", class="TickitUserBundle:User")
     *
     * @return Response
     */
    public function editUserFormAction(User $user)
    {
        $form = $this->createForm('tickit_user', $user);

        return $this->render('TickitUserBundle:User:edit.html.twig', array('form' => $form->createView()));
    }
}
