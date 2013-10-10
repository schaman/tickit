<?php

namespace Tickit\PreferenceBundle\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Tickit\CoreBundle\Controller\Helper\BaseHelper;
use Tickit\CoreBundle\Filters\Collection\Builder\FilterCollectionBuilder;
use Tickit\PreferenceBundle\Manager\PreferenceManager;

/**
 * Preferences controller.
 *
 * Provides actions for managing system and user preferences
 *
 * @package Tickit\PreferenceBundle\Controller
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class ApiController
{
    /**
     * The filter collection builder
     *
     * @var FilterCollectionBuilder
     */
    protected $filterBuilder;

    /**
     * The preference manager
     *
     * @var PreferenceManager
     */
    protected $preferenceManager;

    /**
     * The base controller helper
     *
     * @var BaseHelper
     */
    protected $baseHelper;

    /**
     * @param FilterCollectionBuilder $filterBuilder     The filter collection builder
     * @param PreferenceManager       $preferenceManager The preference manager
     * @param BaseHelper              $baseHelper        The base controller helper
     */
    public function __construct(
        FilterCollectionBuilder $filterBuilder,
        PreferenceManager $preferenceManager,
        BaseHelper $baseHelper
    ) {
        $this->filterBuilder = $filterBuilder;
        $this->preferenceManager = $preferenceManager;
        $this->baseHelper = $baseHelper;
    }

    /**
     * Lists all preferences for editing (should this just be editAction??)
     *
     * @return JsonResponse
     */
    public function listAction()
    {
        $filters = $this->filterBuilder->buildFromRequest($this->baseHelper->getRequest());
        $preferences = $this->preferenceManager->getRepository()->findByFilters($filters);

        $data = array();
        $decorator = $this->baseHelper->getObjectDecorator();
        foreach ($preferences as $preference) {
            $data[] = $decorator->decorate($preference, array('id', 'name', 'systemName', 'type'));
        }

        return new JsonResponse($data);
    }
}
