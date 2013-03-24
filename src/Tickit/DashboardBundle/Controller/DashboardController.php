<?php

namespace Tickit\DashboardBundle\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Tickit\CoreBundle\Controller\AbstractCoreController;

/**
 * Dashboard Controller.
 *
 * Provides actions for displaying dashboard data
 *
 * @package Tickit\DashboardBundle\Controller
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class DashboardController extends AbstractCoreController
{
    /**
     * Index action for the dashboard
     *
     * @Template("TickitDashboardBundle:Dashboard:index.html.twig")
     *
     * @return array
     */
    public function indexAction()
    {
        return array();
    }
}
