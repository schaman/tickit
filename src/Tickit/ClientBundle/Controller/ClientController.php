<?php

namespace Tickit\ClientBundle\Controller;

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
}
