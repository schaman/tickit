<?php

/*
 * Tickit, an open source web based bug management tool.
 *
 * Copyright (C) 2014  Tickit Project <http://tickit.io>
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
use Tickit\Bundle\ProjectBundle\Form\Type\FilterFormType;
use Tickit\Component\Controller\Helper\BaseHelper;
use Tickit\Component\Controller\Helper\CsrfHelper;
use Tickit\Component\Controller\Helper\FormHelper;
use Tickit\Component\Filter\Collection\Builder\FilterCollectionBuilder;
use Tickit\Bundle\ProjectBundle\Doctrine\Repository\AttributeRepository;
use Tickit\Bundle\ProjectBundle\Doctrine\Repository\ProjectRepository;
use Tickit\Component\Filter\Collection\FilterCollection;
use Tickit\Component\Filter\Map\Project\ProjectFilterMapper;
use Tickit\Component\HttpFoundation\Response\RawJsonResponse;
use Tickit\Component\Pagination\PageData;
use Tickit\Component\Pagination\Response\PaginatedJsonResponse;
use Tickit\Component\Pagination\Resolver\PageResolver;

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
     * The form helper
     *
     * @var FormHelper
     */
    protected $formHelper;

    /**
     * Constructor.
     *
     * @param FilterCollectionBuilder $filterBuilder       The filter collection builder
     * @param ProjectRepository       $projectRepository   The project repository
     * @param AttributeRepository     $attributeRepository The attribute repository
     * @param BaseHelper              $baseHelper          The base controller helper
     * @param CsrfHelper              $csrfHelper          The CSRF controller helper
     * @param FormHelper              $formHelper          The form controller helper
     */
    public function __construct(
        FilterCollectionBuilder $filterBuilder,
        ProjectRepository $projectRepository,
        AttributeRepository $attributeRepository,
        BaseHelper $baseHelper,
        CsrfHelper $csrfHelper,
        FormHelper $formHelper
    ) {
        $this->filterBuilder = $filterBuilder;
        $this->projectRepository = $projectRepository;
        $this->attributeRepository = $attributeRepository;
        $this->baseHelper = $baseHelper;
        $this->csrfHelper = $csrfHelper;
        $this->formHelper = $formHelper;
    }

    /**
     * Lists all projects in the application
     *
     * @param integer $page The page of results to return
     *
     * @return RawJsonResponse
     */
    public function listAction($page = 1)
    {
        $request = $this->baseHelper->getRequest();
        $form = $this->formHelper->createForm(new FilterFormType(), null);
        $form->submit($request);

        $filters = $this->filterBuilder->buildFromArray($form->getData(), new ProjectFilterMapper());
        $projects = $this->projectRepository->findByFilters($filters, $page);

        $data = PageData::create($projects, $projects->count(), PageResolver::ITEMS_PER_PAGE, $page);

        return new RawJsonResponse($this->baseHelper->getSerializer()->serialize($data));
    }

    /**
     * Lists all project attributes in the application
     *
     * @param integer $page The page of results to return
     *
     * @return JsonResponse
     */
    public function attributesListAction($page = 1)
    {
        $attributes = $this->attributeRepository->findByFilters(new FilterCollection(), $page);

        $data = PageData::create($attributes, count($attributes), PageResolver::ITEMS_PER_PAGE, $page);

        return new RawJsonResponse($this->baseHelper->getSerializer()->serialize($data));
    }
}
