<?php

/*
 * Tickit, an open source web based bug management tool.
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
 */

namespace Tickit\Bundle\ProjectBundle\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Tickit\Component\Controller\Helper\BaseHelper;
use Tickit\Component\Controller\Helper\CsrfHelper;
use Tickit\Component\Filter\Collection\Builder\FilterCollectionBuilder;
use Tickit\Bundle\ProjectBundle\Doctrine\Repository\AttributeRepository;
use Tickit\Bundle\ProjectBundle\Doctrine\Repository\ProjectRepository;

/**
 * Api project controller.
 *
 * Provides api related actions for projects.
 *
 * @package Tickit\Bundle\ProjectBundle\Controller
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
     * The project repository
     *
     * @var ProjectRepository
     */
    protected $projectRepository;

    /**
     * Attribute repository
     *
     * @var AttributeRepository
     */
    protected $attributeRepository;

    /**
     * The base controller helper
     *
     * @var BaseHelper
     */
    protected $baseHelper;

    /**
     * The CSRF controller helper
     *
     * @var CsrfHelper
     */
    protected $csrfHelper;

    /**
     * Constructor.
     *
     * @param FilterCollectionBuilder $filterBuilder       The filter collection builder
     * @param ProjectRepository       $projectRepository   The project repository
     * @param AttributeRepository     $attributeRepository The attribute repository
     * @param BaseHelper              $baseHelper          The base controller helper
     * @param CsrfHelper              $csrfHelper          The CSRF controller helper
     */
    public function __construct(
        FilterCollectionBuilder $filterBuilder,
        ProjectRepository $projectRepository,
        AttributeRepository $attributeRepository,
        BaseHelper $baseHelper,
        CsrfHelper $csrfHelper
    ) {
        $this->filterBuilder = $filterBuilder;
        $this->projectRepository = $projectRepository;
        $this->attributeRepository = $attributeRepository;
        $this->baseHelper = $baseHelper;
        $this->csrfHelper = $csrfHelper;
    }

    /**
     * Lists all projects in the application
     *
     * @return JsonResponse
     */
    public function listAction()
    {
        $filters = $this->filterBuilder->buildFromRequest($this->baseHelper->getRequest());
        $projects = $this->projectRepository->findByFilters($filters);
        $decorator = $this->baseHelper->getObjectCollectionDecorator();

        $staticProperties = [
            'csrf_token' => $this->csrfHelper->generateCsrfToken(ProjectController::CSRF_DELETE_INTENTION)
        ];

        $data = $decorator->decorate($projects, ['id', 'name', 'created'], $staticProperties);

        return new JsonResponse($data);
    }

    /**
     * Lists all project attributes in the application
     *
     * @return JsonResponse
     */
    public function attributesListAction()
    {
        $filters = $this->filterBuilder->buildFromRequest($this->baseHelper->getRequest());

        $attributes = $this->attributeRepository->findByFilters($filters);

        $decorator = $this->baseHelper->getObjectCollectionDecorator();
        $data = $decorator->decorate($attributes, ['id', 'type', 'name']);

        return new JsonResponse($data);
    }
}
