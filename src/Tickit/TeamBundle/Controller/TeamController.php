<?php

namespace Tickit\TeamBundle\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Tickit\CoreBundle\Controller\AbstractCoreController;

//bind forms here

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Controller that provides actions for managing teams in the application
 *
 * @author James Halsall <james.t.halsall@googlemail.com>
 */
class TeamController extends AbstractCoreController
{
    /**
     * Lists all teams in the system, optionally filters using request parameters
     *
     * @return array
     * @Template("TickitTeamBundle:Team:index.html.twig")
     */
    public function indexAction()
    {
        $projects = $this->get('tickit_team.manager')
            ->getRepository()
            ->findByFilters();

        $token = $this->get('form.csrf_provider')->generateCsrfToken('delete_project');

        return array('teams' => $projects, 'token' => $token);
    }

    /**
     * Loads the create team page
     *
     * @Template("TickitTeamBundle:Team:create.html.twig")
     *
     * @return array|RedirectResponse
     */
    public function createAction()
    {
        $formType = new TeamFormType();
        $form = $this->createForm($formType);

        if ('POST' == $this->getRequest()->getMethod()) {
            $form->bind($this->getRequest());

            if ($form->isValid()) {
                $team = $form->getData();

                /** @var TeamManager $manager  */
                $manager = $this->get('tickit_team.manager');
                $manager->create($team);

                $generator = $this->get('tickit.flash_messages');
                $this->get('session')->getFlashBag()->add('notice', $generator->getEntityCreatedMessage('team'));
                $route = $this->generateUrl('team_index');

                return $this->redirect($route);
            }
        }

        return array('form' => $form->createView());
    }
}