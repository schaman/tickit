<?php

namespace Tickit\ProjectBundle\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Tickit\CoreBundle\Controller\AbstractCoreController;
use Tickit\ProjectBundle\Entity\AbstractAttribute;

/**
 * Api project controller.
 *
 * Provides api related actions for projects.
 *
 * @package Tickit\ProjectBundle\Controller
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class ApiController extends AbstractCoreController
{
    /**
     * Lists all projects in the application
     *
     * @return JsonResponse
     */
    public function listAction()
    {
        $filters = $this->get('tickit.filter_collection_builder')
                        ->buildFromRequest($this->getRequest());

        $projects = $this->get('tickit_project.manager')
                         ->getRepository()
                         ->findByFilters($filters);

        $data = array();
        $decorator = $this->getArrayDecorator();
        $staticProperties = array('csrf_token' => $this->generateCsrfToken(ProjectController::CSRF_DELETE_INTENTION));
        foreach ($projects as $project) {
            $data[] = $decorator->decorate($project, array('id', 'name', 'created'), $staticProperties);
        }

        return new JsonResponse($data);
    }

    /**
     * Lists all project attributes in the application
     *
     * @return JsonResponse
     */
    public function attributesListAction()
    {
        $filters = $this->get('tickit.filter_collection_builder')
                        ->buildFromRequest($this->getRequest());

        $attributes = $this->get('tickit_project.attribute_manager')
                           ->getRepository()
                           ->findByFilters($filters);

        $data = array();
        $decorator = $this->getArrayDecorator();
        /** @var AbstractAttribute $attribute */
        foreach ($attributes as $attribute) {
            $data[] = $decorator->decorate($attribute, array('id', 'type', 'name'));
        }

        return new JsonResponse($data);
    }
}
