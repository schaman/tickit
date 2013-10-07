<?php

namespace Tickit\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Csrf\CsrfTokenGeneratorInterface;
use Tickit\CoreBundle\Decorator\DomainObjectDecoratorInterface;

/**
 * Core controller helper.
 *
 * Provides helper methods for controllers in the application.
 *
 * @package Tickit\CoreBundle\Controller
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class ControllerHelper
{
    /**
     * The request
     *
     * @var Request
     */
    protected $request;

    /**
     * A CSRF token generator
     *
     * @var CsrfTokenGeneratorInterface
     */
    protected $tokenGenerator;

    /**
     * A domain object decorator
     *
     * @var DomainObjectDecoratorInterface
     */
    protected $objectDecorator;

    /**
     * A template engine
     *
     * @var EngineInterface
     */
    protected $templateEngine;

    /**
     * Constructor.
     *
     * @param Request                        $request         The request
     * @param CsrfTokenGeneratorInterface    $tokenGenerator  A token generator
     * @param DomainObjectDecoratorInterface $objectDecorator A domain object decorator
     * @param EngineInterface                $templateEngine  A template engine
     */
    public function __construct(
        Request $request,
        CsrfTokenGeneratorInterface $tokenGenerator,
        DomainObjectDecoratorInterface $objectDecorator,
        EngineInterface $templateEngine
    ) {
        $this->request = $request;
        $this->tokenGenerator = $tokenGenerator;
        $this->objectDecorator = $objectDecorator;
        $this->templateEngine = $templateEngine;
    }

    /**
     * Gets the domain object decorator
     *
     * @return DomainObjectDecoratorInterface
     */
    public function getObjectDecorator()
    {
        return $this->objectDecorator;
    }

    /**
     * Checks a CSRF token for validity.
     *
     * @param string $token  The CSRF token to check
     * @param string $intent The intent of the token
     *
     * @throws NotFoundHttpException If the token is not valid
     *
     * @return void
     */
    public function checkCsrfToken($token, $intent)
    {
        $tokenProvider = $this->tokenGenerator;

        if (!$tokenProvider->isCsrfTokenValid($intent, $token)) {
            throw new NotFoundHttpException('Invalid CSRF token');
        }
    }

    /**
     * Generates a new CSRF token for the given intent
     *
     * @param string $intent The intent of the CSRF token
     *
     * @return string
     */
    public function generateCsrfToken($intent)
    {
        return $this->tokenGenerator->generateCsrfToken($intent);
    }

    /**
     * Gets a rendered form's content inside a given template
     *
     * @param string $template         The template to render the form with
     * @param Form   $form             The form to render
     * @param array  $additionalParams Any additional view parameters
     *
     * @return string
     */
    public function renderForm($template, Form $form, array $additionalParams = array())
    {
        return $this->templateEngine->render(
            $template,
            array_merge(array('form' => $form->createView()), $additionalParams)
        );
    }
}
