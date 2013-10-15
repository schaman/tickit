<?php

namespace Tickit\PreferenceBundle\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Tickit\CoreBundle\Controller\Helper\BaseHelper;
use Tickit\CoreBundle\Filters\Collection\Builder\FilterCollectionBuilder;
use Tickit\PreferenceBundle\Entity\Repository\PreferenceRepository;

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
     * The preference repository
     *
     * @var PreferenceRepository
     */
    protected $preferenceRepository;

    /**
     * The base controller helper
     *
     * @var BaseHelper
     */
    protected $baseHelper;

    /**
     * @param FilterCollectionBuilder $filterBuilder        The filter collection builder
     * @param PreferenceRepository    $preferenceRepository The preference repository
     * @param BaseHelper              $baseHelper           The base controller helper
     */
    public function __construct(
        FilterCollectionBuilder $filterBuilder,
        PreferenceRepository $preferenceRepository,
        BaseHelper $baseHelper
    ) {
        $this->filterBuilder = $filterBuilder;
        $this->preferenceRepository = $preferenceRepository;
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
        $preferences = $this->preferenceRepository->findByFilters($filters);

        $data = array();
        $decorator = $this->baseHelper->getObjectDecorator();
        foreach ($preferences as $preference) {
            $data[] = $decorator->decorate($preference, array('id', 'name', 'systemName', 'type'));
        }

        return new JsonResponse($data);
    }
}
