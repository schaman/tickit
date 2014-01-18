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

namespace Tickit\Bundle\NotificationBundle\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Tickit\Component\Controller\Helper\BaseHelper;
use Tickit\Component\Notification\Provider\NotificationProvider;

/**
 * Notification API controller.
 *
 * Serves JSON data containing notification data.
 *
 * @package Tickit\Bundle\NotificationBundle\Controller
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
     * @param mixed $since The date and time to return notifications since (optional)
     *
     * @return JsonResponse
     */
    public function listAction($since = null)
    {
        if (null !== $since) {
            $since = new \DateTime($since);
        }

        $notifications = $this->provider->findUnreadForUser($this->baseHelper->getUser(), $since);

        $decorator = $this->baseHelper->getObjectCollectionDecorator();
        $data = $decorator->decorate($notifications, ['message', 'createdAt', 'actionUri']);

        return new JsonResponse($data);
    }
}
