<?php

namespace Tickit\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * Default application controller.
 *
 * Provides a default entry-point for requests, usually used to
 * serve the base page template in the application.
 *
 * @package Tickit\CoreBundle\Controller
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class DefaultController extends Controller
{
    /**
     * Renders the base template content.
     *
     * @return string
     */
    public function defaultAction()
    {
        return new Response($this->renderView('::base.html.twig'));
    }
}
