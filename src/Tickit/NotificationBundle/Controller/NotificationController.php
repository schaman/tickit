<?php

namespace Tickit\NotificationBundle\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Notification controller.
 *
 * Provides notification related actions for modify/updating data
 *
 * @package Tickit\NotificationBundle\Controller
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class NotificationController
{
    /**
     * Marks a notification as being read
     *
     * @todo
     *
     * @return JsonResponse
     */
    public function markAsReadAction()
    {
        return new JsonResponse(array('success' => false));
    }
}
