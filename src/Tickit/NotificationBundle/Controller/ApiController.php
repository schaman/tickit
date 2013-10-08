<?php

namespace Tickit\NotificationBundle\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Tickit\CoreBundle\Controller\Helper\ControllerHelper;

/**
 * Notification API controller.
 *
 * Serves JSON data containing notification data.
 *
 * @package Tickit\NotificationBundle\Controller
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class ApiController extends ControllerHelper
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
        $notifications = $this->get('tickit_notification.provider')
                              ->findUnreadForUser($this->getUser());

        $data = array();
        $decorator = $this->getArrayDecorator();
        foreach ($notifications as $notification) {
            $data[] = $decorator->decorate($notification, array('message', 'createdAt', 'actionUri'));
        }

        return new JsonResponse($data);
    }
}
