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
     * @return Response
     */
    public function defaultAction()
    {
        return $this->templateEngine->renderResponse('::base.html.twig');
    }
}
