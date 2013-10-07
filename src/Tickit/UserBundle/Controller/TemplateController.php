<?php

namespace Tickit\UserBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Response;
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
class TemplateController extends Controller
{
    /**
     * The user manager
     *
     * @var UserManager
     */
    protected $userManager;

    /**
     * A form factory
     *
     * @var FormFactoryInterface
     */
    protected $formFactory;

    /**
     * A template engine
     *
     * @var EngineInterface
     */
    protected $templateEngine;

    /**
     * Constructor.
     *
     * @param UserManager          $userManager    The user manager
     * @param FormFactoryInterface $formFactory    A form factory
     * @param EngineInterface      $templateEngine A template engine
     */
    public function __construct(
        UserManager $userManager,
        FormFactoryInterface $formFactory,
        EngineInterface $templateEngine
    ) {
        $this->userManager = $userManager;
        $this->formFactory = $formFactory;
        $this->templateEngine = $templateEngine;
    }

    /**
     * Create user form action.
     *
     * Serves the form markup for creating a user.
     *
     * @return Response
     */
    public function createUserFormAction()
    {
        $user = $this->userManager->createUser();
        $form = $this->formFactory->create('tickit_user', $user);

        return $this->templateEngine->renderResponse(
            'TickitUserBundle:User:create.html.twig',
            array('form' => $form->createView())
        );
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
        $form = $this->formFactory->create('tickit_user', $user);

        return $this->templateEngine->renderResponse(
            'TickitUserBundle:User:edit.html.twig',
            array('form' => $form->createView())
        );
    }
}
