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

namespace Tickit\Bundle\IssueBundle\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Tickit\Bundle\IssueBundle\Form\Type\FilterFormType;
use Tickit\Component\Controller\Helper\BaseHelper;
use Tickit\Component\Controller\Helper\CsrfHelper;
use Tickit\Component\Controller\Helper\FormHelper;
use Tickit\Component\Filter\Collection\Builder\FilterCollectionBuilder;
use Tickit\Component\Filter\Map\Issue\IssueFilterMapper;
use Tickit\Component\Filter\Repository\FilterableRepositoryInterface;
use Tickit\Component\Pagination\Resolver\PageResolver;
use Tickit\Component\Pagination\Response\PaginatedJsonResponse;

/**
 * Issue API controller.
 *
 * Serves API actions for issues.
 *
 * @package Tickit\Bundle\IssueBundle\Controller
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class ApiController
{
    /**
     * A filter collection builder
     *
     * @var FilterCollectionBuilder
     */
    private $filterBuilder;

    /**
     * The base controller helper
     *
     * @var BaseHelper
     */
    private $baseHelper;

    /**
     * The CSRF controller helper
     *
     * @var CsrfHelper
     */
    private $csrfHelper;

    /**
     * A filterable issue repository
     *
     * @var FilterableRepositoryInterface
     */
    private $issueRepository;

    /**
     * The form controller helper
     *
     * @var FormHelper
     */
    private $formHelper;

    /**
     * Constructor.
     *
     * @param FilterCollectionBuilder       $filterBuilder    The filter collection builder
     * @param BaseHelper                    $baseHelper       The base controller helper
     * @param CsrfHelper                    $csrfHelper       The CSRF controller helper
     * @param FilterableRepositoryInterface $issueRepository  The issue repository
     * @param FormHelper                    $formHelper       The form controller helper
     */
    public function __construct(
        FilterCollectionBuilder $filterBuilder,
        BaseHelper $baseHelper,
        CsrfHelper $csrfHelper,
        FilterableRepositoryInterface $issueRepository,
        FormHelper $formHelper
    ) {
        $this->filterBuilder   = $filterBuilder;
        $this->baseHelper      = $baseHelper;
        $this->csrfHelper      = $csrfHelper;
        $this->issueRepository = $issueRepository;
        $this->formHelper      = $formHelper;
    }

    /**
     * Lists all issues in the application
     *
     * @param integer $page The page number of results to return
     *
     * @return JsonResponse
     */
    public function listAction($page = 1)
    {
        $form = $this->formHelper->createForm(new FilterFormType(), null);
        $form->submit($this->baseHelper->getRequest());

        $filters = $this->filterBuilder->buildFromArray($form->getData(), new IssueFilterMapper());
        $issues = $this->issueRepository->findByFilters($filters, $page);

        $decorator = $this->baseHelper->getObjectCollectionDecorator();
        $propertyMappings = [
            'project.name' => 'project',
            'type.name' => 'type',
            'status.name' => 'status',
            'assignedTo.fullName' => 'assignedTo',
            'number.issueNumber' => 'number'
        ];
        $decorator->setPropertyMappings($propertyMappings);
        $data = $decorator->decorate(
            $issues->getIterator(),
            ['id', 'number', 'title', 'project.name', 'priority', 'type.name', 'status.name', 'assignedTo', 'createdBy', 'dueDate', 'createdAt', 'updatedAt'],
            ['csrfToken' => $this->csrfHelper->generateCsrfToken(IssueController::CSRF_DELETE_INTENTION)->getValue()]
        );

        return new PaginatedJsonResponse($data, $issues->count(), PageResolver::ITEMS_PER_PAGE, $page);
    }
}
