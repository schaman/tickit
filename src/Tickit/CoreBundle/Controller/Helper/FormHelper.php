<?php

namespace Tickit\CoreBundle\Controller\Helper;

use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\Form\FormInterface;

/**
 * Form controller helper.
 *
 * Provides helper methods for handling forms in controllers.
 *
 * @package Tickit\CoreBundle\Controller|Helper
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class FormHelper
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
     * @param EngineInterface $templateEngine The templating engine
     */
    public function __construct(EngineInterface $templateEngine)
    {
        $this->templateEngine = $templateEngine;
    }

    /**
     * Gets a rendered form's content inside a given template
     *
     * @param string        $template         The template to render the form with
     * @param FormInterface $form             The form to render
     * @param array         $additionalParams Any additional view parameters
     *
     * @return string
     */
    public function renderForm($template, FormInterface $form, array $additionalParams = array())
    {
        return $this->templateEngine->render(
            $template,
            array_merge(array('form' => $form->createView()), $additionalParams)
        );
    }
}
