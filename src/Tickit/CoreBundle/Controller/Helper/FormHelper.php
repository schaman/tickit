<?php

namespace Tickit\CoreBundle\Controller\Helper;

use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormFactoryInterface;
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
     * A form factory
     *
     * @var FormFactoryInterface
     */
    protected $formFactory;

    /**
     * Constructor.
     *
     * @param EngineInterface      $templateEngine The templating engine
     * @param FormFactoryInterface $formFactory    A form factory
     */
    public function __construct(EngineInterface $templateEngine, FormFactoryInterface $formFactory)
    {
        $this->templateEngine = $templateEngine;
        $this->formFactory = $formFactory;
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

    /**
     * Creates a form from a type
     *
     * @param mixed $type    The form type to create
     * @param mixed $data    The data to instantiate the form with
     * @param array $options An optional array of form options
     *
     * @return Form
     */
    public function createForm($type, $data, array $options = null)
    {
        return $this->formFactory->create($type, $data, $options);
    }
}
