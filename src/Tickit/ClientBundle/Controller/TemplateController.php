<?php

namespace Tickit\ClientBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use Tickit\ClientBundle\Entity\Client;
use Tickit\CoreBundle\Controller\Helper\FormHelper;

/**
 * Template controller.
 *
 * Servers client related templates.
 *
 * @package Tickit\ClientBundle\Controller
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
