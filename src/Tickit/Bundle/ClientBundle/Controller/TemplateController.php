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

namespace Tickit\Bundle\ClientBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use Tickit\Component\Model\Client\Client;
use Tickit\Component\Controller\Helper\FormHelper;

/**
 * Template controller.
 *
 * Servers client related templates.
 *
 * @package Tickit\Bundle\ClientBundle\Controller
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class TemplateController
{
    /**
     * The form controller helper
     *
     * @var FormHelper
     */
    protected $formHelper;

    /**
     * Constructor.
     *
     * @param FormHelper $formHelper The form controller helper
     */
    public function __construct(FormHelper $formHelper)
    {
        $this->formHelper = $formHelper;
    }

    /**
     * Create client form action.
     *
     * Serves the form markup for creating a new client.
     *
     * @return Response
     */
    public function createClientFormAction()
    {
        $form = $this->formHelper->createForm('tickit_client', new Client());

        return $this->formHelper->renderForm('TickitClientBundle:Client:create.html.twig', $form);
    }

    /**
     * Edit client form action.
     *
     * Serves the form markup for editing an existing client.
     *
     * @param Client $client The client that is being edited
     *
     * @return Response
     */
    public function editClientFormAction(Client $client)
    {
        $form = $this->formHelper->createForm('tickit_client', $client);

        return $this->formHelper->renderForm('TickitClientBundle:Client:edit.html.twig', $form);
    }
}
