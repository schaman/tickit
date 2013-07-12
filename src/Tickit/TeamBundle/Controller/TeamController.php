<?php

namespace Tickit\TeamBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Tickit\CoreBundle\Controller\AbstractCoreController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Tickit\TeamBundle\Form\Type\TeamFormType;
use Tickit\TeamBundle\Manager\TeamManager;

/**
 * Teams controller.
 *
 * Responsible for handling requests related to teams
 *
 * @package Tickit\TeamBundle\Controller
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class TeamController extends AbstractCoreController
{
    /**
     * Lists all teams in the application
     *
     * @Template("TickitTeamBundle:Team:index.html.twig")
     *
     * @return array
     */
    public function indexAction()
    {
        $teams = $this->get('tickit_team.manager')
                      ->getRepository()
                      ->findByFilters();

        $token = $this->get('form.csrf_provider')->generateCsrfToken('delete_team');

        return array('teams' => $teams, 'token' => $token);
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
            $form->submit($this->getRequest());

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

    /**
     * Loads the edit team page
     *
     * @param integer $id The ID of the team to edit
     *
     * @Template("TickitTeamBundle:Team:edit.html.twig")
     *
     * @throws NotFoundHttpException If no team was found for the given ID
     *
     * @return array
     */
    public function editAction($id)
    {
        /** @var TeamManager $manager */
        $manager = $this->get('tickit_team.manager');
        $repo = $manager->getRepository();

        $team = $repo->find($id);

        if (empty($team)) {
            throw $this->createNotFoundException('Team not found');
        }

        $formType = new TeamFormType();
        $form = $this->createForm($formType, $team);

        if ('POST' === $this->getRequest()->getMethod()) {
            $form->bind($this->getRequest());

            if ($form->isValid()) {
                $team = $form->getData();
                $manager->update($team);

                $generator = $this->get('tickit.flash_messages');
                $this->get('session')->getFlashbag()->add('notice', $generator->getEntityUpdatedMessage('team'));
            }
        }

        return array('form' => $form->createView());
    }

    /**
     * Deletes a team from the application.
     *
     * @param integer $id The ID of the team to delete
     *
     * @return RedirectResponse
     */
    public function deleteAction($id)
    {
        $token = $this->getRequest()->query->get('token');
        $tokenProvider = $this->get('form.csrf_provider');

        if (!$tokenProvider->isCsrfTokenValid('delete_team', $token)) {
            throw $this->createNotFoundException('Invalid CSRF token');
        }

        /** @var TeamManager $manager  */
        $manager = $this->get('tickit_team.manager');
        $team = $manager->getRepository()->find($id);

        if (empty($team)) {
            throw $this->createNotFoundException('Team not found');
        }

        $manager->delete($team);

        $generator = $this->get('tickit.flash_messages');
        $this->get('session')->getFlashBag()->add('notice', $generator->getEntityDeletedMessage('team'));

        $route = $this->generateUrl('team_index');

        return $this->redirect($route);
    }
}
