<?php

/*
 * Tickit, an open source web based bug management tool.
 * 
 * Copyright (C) 2013  Tickit Project <http://tickit.io>
 * 
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Tickit\Component\Controller\Helper;

use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 * Form controller helper.
 *
 * Provides helper methods for handling forms in controllers.
 *
 * @package Tickit\Bundle\CoreBundle\Controller|Helper
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
     * @return Response
     */
    public function renderForm($template, FormInterface $form, array $additionalParams = array())
    {
        $content = $this->templateEngine->render(
            $template,
            array_merge(array('form' => $form->createView()), $additionalParams)
        );

        return new Response($content);
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
    public function createForm($type, $data, array $options = array())
    {
        return $this->formFactory->create($type, $data, $options);
    }
}
