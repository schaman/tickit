<?php

namespace Tickit\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
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
class DefaultController
{
    /**
     * A template engine
     *
     * @var EngineInterface
     */
    protected $templateEngine;

    /**
     * Constructor.
     *
     * @param EngineInterface $templateEngine A templating engine
     */
    public function __construct(EngineInterface $templateEngine)
    {
        $this->templateEngine = $templateEngine;
    }

    /**
     * Renders the base template content.
     *
     * @return string
     */
    public function defaultAction()
    {
        return $this->templateEngine->renderResponse('::base.html.twig');
    }
}
