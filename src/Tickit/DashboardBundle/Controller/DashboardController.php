<?php

namespace Tickit\DashboardBundle\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Tickit\CoreBundle\Controller\CoreController;

/**
 * Controller that provides actions for displaying dashboard data
 *
 * @author James Halsall <james.t.halsall@googlemail.com>
 */
class DashboardController extends CoreController
{
    /**
     * Index action for the dashboard
     *
     * @Template("TickitDashboardBundle:Dashboard:index.html.twig")
     */
    public function indexAction()
    {
        return array();
    }

}