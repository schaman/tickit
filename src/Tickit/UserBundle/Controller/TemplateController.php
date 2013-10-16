<?php

namespace Tickit\UserBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use Tickit\CoreBundle\Controller\Helper\FormHelper;
use Tickit\UserBundle\Entity\User;
use Tickit\UserBundle\Manager\UserManager;

/**
 * Template controller.
 *
 * Serves dynamic templates for the user bundle.
 *
 * @package Tickit\UserBundle\Controller
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class TemplateController
{
    /**
     * The user manager
     *
     * @var UserManager
     */
    protected $userManager;

    /**
     * The form helper
     *
     * @var FormHelper
     */
    protected $formHelper;

    /**
     * Constructor.
     *
     * @param UserManager $userManager The user manager
     * @param FormHelper  $formHelper  The form helper
     */
    public function __construct(UserManager $userManager, FormHelper $formHelper)
    {
        $this->userManager = $userManager;
        $this->formHelper = $formHelper;
    }

    /**
     * Create user form action.
     *
     * Serves the form markup for creating a user.
     *
     * @return Response
     */
    public function createFormAction()
    {
        $user = $this->userManager->createUser();
        $form = $this->formHelper->createForm('tickit_user', $user);

        return $this->formHelper->renderForm('TickitUserBundle:User:create.html.twig', $form);
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
    public function editFormAction(User $user)
    {
        $form = $this->formHelper->createForm('tickit_user', $user);

        return $this->formHelper->renderForm('TickitUserBundle:User:edit.html.twig', $form);
    }
}
