<?php

namespace Tickit\TeamBundle\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Tickit\CoreBundle\Controller\AbstractCoreController;

/**
 * API Controller.
 *
 * Provides api related actions for the team bundle
 *
 * @package Tickit\TeamBundle\Controller
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class ApiController extends AbstractCoreController
{
    /**
     * List teams in the application.
     *
     * @param integer $page The page of data to display
     *
     * @return JsonResponse
     */
    public function listAction($page = 1)
    {
        $filters = $this->get('tickit.filter_collection_builder')
                        ->buildFromRequest($this->getRequest());

        $teams = $this->get('tickit_team.manager')
                      ->getRepository()
                      ->findByFilters($filters);

        $data = array();
        $decorator = $this->getArrayDecorator();
        foreach ($teams as $team) {
            $data[] = $decorator->decorate($team, array('id', 'name'));
        }

        return new JsonResponse($data);
    }
}
