<?php

namespace Tickit\TeamBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Tickit\CoreBundle\Controller\AbstractCoreController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Teams controller.
 *
 * Provides actions for managing teams in the application
 *
 * @package Tickit\TeamBundle\Controller
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class TeamController extends AbstractCoreController
{
    /**
     * Lists all teams in the system, optionally filters using request parameters
     *
     * @Template("TickitTeamBundle:Team:index.html.twig")
     *
     * @return array
     */
    public function indexAction()
    {
        $options = array(); //fetch filters from request

        $teamsQ = $this->getDoctrine()
                       ->getManager()
                       ->getRepository('TickitTeamBundle:Team')
                       ->getFiltered($options);

        $teams = $teamsQ->getQuery()->execute();

        return array('teams' => $teams);
    }
}
