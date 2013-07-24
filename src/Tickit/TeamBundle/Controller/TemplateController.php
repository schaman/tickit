<?php

namespace Tickit\TeamBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use Tickit\CoreBundle\Controller\AbstractCoreController;
use Tickit\TeamBundle\Entity\Team;
use Tickit\TeamBundle\Form\Type\TeamFormType;

/**
 * Template controller.
 *
 * Serves template content for the teams bundle.
 *
 * @package Tickit\TeamBundle\Controller
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class TemplateController extends AbstractCoreController
{
    /**
     * Create team form action.
     *
     * Serves a create form for creating a team.
     *
     * @return Response
     */
    public function createTeamFormAction()
    {
        $team = new Team();
        $form = $this->createForm(new TeamFormType(), $team);

        return $this->render('TickitTeamBundle:Team:create.html.twig', array('form' => $form->createView()));
    }

    /**
     * Edit team form action.
     *
     * Serves an edit form for updating a team.
     *
     * @param Team $team The team that is going to be updated
     *
     * @return Response
     */
    public function editTeamFormAction(Team $team)
    {
        $form = $this->createForm(new TeamFormType(), $team);

        return $this->render('TickitTeamBundle:Team:edit.html.twig', array('form' => $form->createView()));
    }
}
