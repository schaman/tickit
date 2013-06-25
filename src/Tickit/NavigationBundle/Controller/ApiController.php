<?php

namespace Tickit\NavigationBundle\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Tickit\CoreBundle\Controller\AbstractCoreController;

/**
 * Provides actions related to the application navigation
 *
 * @package Tickit\NavigationBundle\Controller
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class ApiController extends AbstractCoreController
{
    /**
     * Lists available navigation items for the currently authenticated user
     *
     * @todo This needs to be built dynamically using events
     *
     * @return JsonResponse
     */
    public function navItemsAction()
    {
        $data = array(
            array(
                'name' => 'Dashboard',
                'uri' => $this->generateUrl('dashboard_index'),
                'active' => false
            ),
            array(
                'name' => 'Tickets',
                'uri' => '#',
                'active' => false
            ),
            array(
                'name' => 'Projects',
                'uri' => $this->generateUrl('project_index'),
                'active' => false
            ),
            array(
                'name' => 'Users',
                'uri' => $this->generateUrl('user_index'),
                'active' => false
            ),
            array(
                'name' => 'Preferences',
                'uri' => $this->generateUrl('preference_index'),
                'active' => false
            )
        );

        return new JsonResponse($data);
    }
}
