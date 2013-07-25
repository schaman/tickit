<?php

namespace Tickit\NotificationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Notification API controller.
 *
 * Serves JSON data containing notification data.
 *
 * @package Tickit\NotificationBundle\Controller
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class ApiController extends Controller
{
    /**
     * List action.
     *
     * Lists all notifications for the current user
     *
     * @return JsonResponse
     */
    public function listAction()
    {
        return new JsonResponse(array());
    }
}
