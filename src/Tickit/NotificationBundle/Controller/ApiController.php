<?php

namespace Tickit\NotificationBundle\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Tickit\CoreBundle\Controller\Helper\BaseHelper;
use Tickit\NotificationBundle\Provider\NotificationProvider;

/**
 * Notification API controller.
 *
 * Serves JSON data containing notification data.
 *
 * @package Tickit\NotificationBundle\Controller
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class ApiController
{
    /**
     * The notification provider
     *
     * @var NotificationProvider
     */
    protected $provider;

    /**
     * The base controller helper
     *
     * @var BaseHelper
     */
    protected $baseHelper;

    /**
     * Constructor.
     *
     * @param NotificationProvider $provider   The notification provider
     * @param BaseHelper           $baseHelper The base controller helper
     */
    public function __construct(NotificationProvider $provider, BaseHelper $baseHelper)
    {
        $this->provider = $provider;
        $this->baseHelper = $baseHelper;
    }

    /**
     * List action.
     *
     * Lists all notifications for the current user
     *
     * @return JsonResponse
     */
    public function listAction()
    {
        $notifications = $this->provider->findUnreadForUser($this->baseHelper->getUser());

        $data = array();
        $decorator = $this->baseHelper->getObjectDecorator();
        foreach ($notifications as $notification) {
            $data[] = $decorator->decorate($notification, array('message', 'createdAt', 'actionUri'));
        }

        return new JsonResponse($data);
    }
}
