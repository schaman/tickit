<?php

namespace Tickit\CoreBundle\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Provides actions related to the application navigation
 *
 * @package Tickit\CoreBundle\Controller
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class NavigationController extends AbstractCoreController
{

    /**
     * Lists available navigation items for the currently authenticated user
     *
     * @todo This needs to be built dynamically using events
     * @todo Possibly move this to an ApiController
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

    /**
     * Renders the top navigation bar for the application based on the current user type
     *
     * @deprecated Since moving to backbone
     *
     * @return Response
     */
    public function topNavigationAction()
    {
        /** @var \Symfony\Component\Routing\Router $router */
        $router = $this->get('router');

        $items = array(
            array(
                'name' => 'Dashboard',
                'route' => $router->generate('dashboard_index')
            ),
            array(
                'name' => 'Tickets',
                'route' => '#'
            ),
            array(
                'name' => 'Projects',
                'route' => $router->generate('project_index')
            ),
            array(
                'name' => 'Teams',
                'route' => $router->generate('team_index')
            ),
            array(
                'name' => 'Users',
                'route' => $router->generate('user_index')
            ),
            array(
                'name' => 'System',
                'route' => '#'
            )
        );

        return $this->render('TickitCoreBundle:Navigation:top-navigation.html.twig', array('navigation' => $items));
    }

    /**
     * Renders the secondary navigation area for specific pages (if applicable -- not all pages contain a
     * second navigation, in which case this method will return an empty string)
     *
     * @todo
     *
     * @return Response
     */
    public function subNavigationAction()
    {
        return $this->render('TickitCoreBundle:Navigation:sub-navigation.html.twig');
    }
}
