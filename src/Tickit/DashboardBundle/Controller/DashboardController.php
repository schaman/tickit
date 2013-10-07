<?php

namespace Tickit\DashboardBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Tickit\CoreBundle\Controller\ControllerHelper;

/**
 * Dashboard Controller.
 *
 * Provides actions for displaying dashboard data
 *
 * @package Tickit\DashboardBundle\Controller
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class DashboardController extends ControllerHelper
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
