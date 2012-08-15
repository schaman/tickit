<?php

namespace Tickit\TeamBundle\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Tickit\CoreBundle\Controller\CoreController;

//bind forms here

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Controller that provides actions for managing teams in the application
 *
 * @author James Halsall <james.t.halsall@googlemail.com>
 */
class TeamController extends CoreController
{
    /**
     * Lists all teams in the system, optionally filters using request parameters
     *
     * @return array
     * @Template("TickitTeamBundle:Team:index.html.twig")
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