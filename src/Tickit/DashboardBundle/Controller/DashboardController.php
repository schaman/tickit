<?php

namespace Tickit\DashboardBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DashboardController extends Controller
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