<?php

namespace Tickit\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Tickit\CoreBundle\Decorator\DomainObjectArrayDecorator;

/**
 * Core controller.
 *
 * Provides base methods for all extending controller classes in the application.
 *
 * @package Tickit\CoreBundle\Controller
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
abstract class AbstractCoreController extends Controller
{
    /**
     * Gets an array decorator
     *
     * @return DomainObjectArrayDecorator
     */
    protected function getArrayDecorator()
    {
        return $this->get('tickit.domain_object_array_decorator');
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
    protected function checkCsrfToken($token, $intent)
    {
        $tokenProvider = $this->get('form.csrf_provider');

        if (!$tokenProvider->isCsrfTokenValid($intent, $token)) {
            throw $this->createNotFoundException('Invalid CSRF token');
        }
    }

    /**
     * Generates a new CSRF token for the given intent
     *
     * @param string $intent The intent of the CSRF token
     *
     * @return string
     */
    protected function generateCsrfToken($intent)
    {
        $tokenProvider = $this->get('form.csrf_provider');

        return $tokenProvider->generateCsrfToken($intent);
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
    protected function renderForm($template, Form $form, array $additionalParams = array())
    {
        return $this->render(
            $template,
            array_merge(array('form' => $form->createView()), $additionalParams)
        )->getContent();
    }
}
