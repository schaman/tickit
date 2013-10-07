<?php

namespace Tickit\PreferenceBundle\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Tickit\CoreBundle\Controller\ControllerHelper;

/**
 * Preferences controller.
 *
 * Provides actions for managing system and user preferences
 *
 * @package Tickit\PreferenceBundle\Controller
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class ApiController extends ControllerHelper
{
    /**
     * Lists all preferences for editing (should this just be editAction??)
     *
     * @return JsonResponse
     */
    public function listAction()
    {
        $filters = $this->get('tickit.filter_collection_builder')
                        ->buildFromRequest($this->getRequest());

        $preferences = $this->get('tickit_preference.manager')
                            ->getRepository()
                            ->findByFilters($filters);

        $data = array();
        $decorator = $this->getArrayDecorator();
        foreach ($preferences as $preference) {
            $data[] = $decorator->decorate($preference, array('id', 'name', 'systemName', 'type'));
        }

        return new JsonResponse($data);
    }
}
