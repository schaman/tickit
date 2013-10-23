<?php

/*
 * 
 * Tickit, an source web based bug management tool.
 * 
 * Copyright (C) 2013  Tickit Project <http://tickit.io>
 * 
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 * 
 */

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

        $decorator = $this->baseHelper->getObjectCollectionDecorator();
        $data = $decorator->decorate($preferences, ['id', 'name', 'systemName', 'type']);

        return new JsonResponse($data);
    }
}
