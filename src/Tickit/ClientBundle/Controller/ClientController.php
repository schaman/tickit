<?php

namespace Tickit\ClientBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Tickit\ClientBundle\Entity\Client;
use Tickit\ClientBundle\Manager\ClientManager;
use Tickit\CoreBundle\Controller\Helper\BaseHelper;
use Tickit\CoreBundle\Controller\Helper\CsrfHelper;
use Tickit\CoreBundle\Controller\Helper\FormHelper;

/**
 * Client controller.
 *
 * Responsible for handling requests related to clients
 *
 * @package Tickit\ClientBundle\Controller
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class ClientController
{
    /**
     * String intention for deleting a client
     *
     * @const string
     */
    const CSRF_DELETE_INTENTION = 'delete_client';

    /**
     * The form controller helper
     *
     * @var FormHelper
     */
    protected $formHelper;

    /**
     * The base controller helper
     *
     * @var BaseHelper
     */
    protected $baseHelper;

    /**
     * The CSRF controller helper
     *
     * @var CsrfHelper
     */
    protected $csrfHelper;

    /**
     * The client manager
     *
     * @var ClientManager
     */
    protected $clientManager;

    /**
     * Constructor.
     *
     * @param FormHelper    $formHelper    The form controller helper
     * @param BaseHelper    $baseHelper    The base controller helper
     * @param CsrfHelper    $csrfHelper    The CSRF controller helper
     * @param ClientManager $clientManager The client manager
     */
    public function __construct(
        FormHelper $formHelper,
        BaseHelper $baseHelper,
        CsrfHelper $csrfHelper,
        ClientManager $clientManager
    ) {
        $this->formHelper = $formHelper;
        $this->baseHelper = $baseHelper;
        $this->csrfHelper = $csrfHelper;
        $this->clientManager = $clientManager;
    }

    /**
     * Create action.
     *
     * Handles a form submission and creates a new Client entity with the form data
     *
     * @return JsonResponse
     */
    public function createAction()
    {
        $responseData = ['success' => false];
        $form = $this->formHelper->createForm('tickit_client', new Client());
        $form->handleRequest($this->baseHelper->getRequest());

        if ($form->isValid()) {
            $this->clientManager->create($form->getData());
            $responseData['success'] = true;
        } else {
            $formResponse = $this->formHelper->renderForm('TickitClientBundle:Client:create.html.twig', $form);
            $responseData['form'] = $formResponse->getContent();
        }

        return new JsonResponse($responseData);
    }

    /**
     * Edit action.
     *
     * Handles a form submission and updates an existing Client entity with the form data
     *
     * @param Client $client The client to edit
     *
     * @ParamConverter("client", class="TickitClientBundle:Client")
     *
     * @return JsonResponse
     */
    public function editAction(Client $client)
    {
        $responseData = ['success' => false];
        $form = $this->formHelper->createForm('tickit_client', $client);
        $form->handleRequest($this->baseHelper->getRequest());

        if ($form->isValid()) {
            $this->clientManager->update($form->getData());
            $responseData['success'] = true;
        } else {
            $formResponse = $this->formHelper->renderForm('TickitClientBundle:Client:edit.html.twig', $form);
            $responseData['form'] = $formResponse->getContent();
        }

        return new JsonResponse($responseData);
    }

    /**
     * Delete action.
     *
     * Handles a delete request from the client.
     *
     * @param Client $client The client to delete
     *
     * @ParamConverter("client", "TickitClientBundle:Client")
     *
     * @return JsonResponse
     */
    public function deleteAction(Client $client)
    {
        $token = $this->baseHelper->getRequest()->query->get('token');
        $this->csrfHelper->checkCsrfToken($token, static::CSRF_DELETE_INTENTION);
        $this->clientManager->delete($client);

        return new JsonResponse(['success' => true]);
    }
}
