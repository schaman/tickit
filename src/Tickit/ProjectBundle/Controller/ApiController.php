<?php

namespace Tickit\ProjectBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Api project controller.
 *
 * Provides api related actions for projects.
 *
 * @package Tickit\ProjectBundle\Controller
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class ApiController extends Controller
{
    /**
     * Lists all projects in the application
     *
     * @return array
     */
    public function listAction()
    {
        $projects = $this->get('tickit_project.manager')
                         ->getRepository()
                         ->findByFilters();

        $data = array();
        // TODO: this would need to go into a decorator
        foreach ($projects as $project) {
            $data[] = array(
                'id' => $project->getId(),
                'name' => $project->getName(),
                'created' => $project->getCreated()->format('Y-m-d H:i:s')
            );
        }

        return new JsonResponse($data);
    }
}
