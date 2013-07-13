<?php

namespace Tickit\TeamBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Tickit\CoreBundle\Controller\AbstractCoreController;
use Tickit\TeamBundle\Entity\Team;
use Tickit\TeamBundle\Form\Type\TeamFormType;

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
     * String constant containing the intention for CSRF delete action
     *
     * @const string
     */
    const CSRF_DELETE_INTENTION = 'delete_team';

    /**
     * Create team action.
     *
     * Handles a request to create a new team in the application.
     *
     * @return JsonResponse
     */
    public function createAction()
    {
        $responseData = array('success' => false);
        $formType = new TeamFormType();
        $form = $this->createForm($formType, new Team());

        $form->submit($this->getRequest());
        if ($form->isValid()) {
            $this->get('tickit_team.manager')->create($form->getData());
            $data['success'] = true;
        } else {
            $data['form'] = $this->render(
                'TickitTeam:Team:create.html.twig',
                array('form' => $form->createView())
            );
        }

        return new JsonResponse($responseData);
    }

    /**
     * Edit team action.
     *
     * Handles a request to update a team in the application.
     *
     * @param Team $team The team to edit
     *
     * @ParamConverter("team", class="TickitTeamBundle:Team")
     *
     * @return JsonResponse
     */
    public function editAction(Team $team)
    {
        $responseData = array('success' => false);
        $form = $this->createForm(new TeamFormType(), $team);
        $form->submit($this->getRequest());

        if ($form->isValid()) {
            $this->get('tickit_team.manager')->update($form->getData());
            $responseData['success'] = true;
        } else {
            $responseData['form'] = $this->render(
                'TickitTeamBundle:Team:edit.html.twig',
                array('form' => $form->createView())
            );
        }

        return new JsonResponse($responseData)
    }

    /**
     * Deletes a team from the application.
     *
     * @param Team $team The team to delete
     *
     * @ParamConverter("team", class="TickitTeamBundle:Team")
     *
     * @throws NotFoundHttpException If the CSRF token is invalid
     *
     * @return JsonResponse
     */
    public function deleteAction(Team $team)
    {
        $token = $this->getRequest()->query->get('token');
        $tokenProvider = $this->get('form.csrf_provider');

        if (!$tokenProvider->isCsrfTokenValid(static::CSRF_DELETE_INTENTION, $token)) {
            throw $this->createNotFoundException('Invalid CSRF token');
        }

        $manager = $this->get('tickit_team.manager');
        $manager->delete($team);

        return new JsonResponse(array('success' => true));
    }
}
