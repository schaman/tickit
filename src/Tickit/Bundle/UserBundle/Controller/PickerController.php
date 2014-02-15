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

namespace Tickit\Bundle\UserBundle\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Tickit\Component\Controller\Helper\BaseHelper;
use Tickit\Component\Filter\Collection\Builder\FilterCollectionBuilder;
use Tickit\Component\Filter\Collection\FilterCollection;
use Tickit\Component\Filter\Map\User\UserFilterMapper;
use Tickit\Component\Filter\Repository\FilterableRepositoryInterface;

/**
 * Picker controller.
 *
 * Responsible for serving requests related to the user picker.
 *
 * @package Tickit\Bundle\UserBundle\Controller
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class PickerController
{
    /**
     * Filterable user repository
     *
     * @var FilterableRepositoryInterface
     */
    private $userRepository;

    /**
     * Filter collection builder
     *
     * @var FilterCollectionBuilder
     */
    private $filterCollectionBuilder;

    /**
     * The base controller helper
     *
     * @var BaseHelper
     */
    private $baseHelper;

    /**
     * Constructor.
     *
     * @param FilterableRepositoryInterface $userRepository          A filterable user repository
     * @param BaseHelper                    $baseHelper              The base controller helper
     * @param FilterCollectionBuilder       $filterCollectionBuilder The filter collection builder
     */
    public function __construct(
        FilterableRepositoryInterface $userRepository,
        BaseHelper $baseHelper,
        FilterCollectionBuilder $filterCollectionBuilder
    ) {
        $this->userRepository           = $userRepository;
        $this->filterCollectionBuilder  = $filterCollectionBuilder;
        $this->baseHelper               = $baseHelper;
    }

    /**
     * Find action.
     *
     * Finds matches for the picker search term.
     *
     * Returns the results in a JSON object
     *
     * @param Request $request The request object
     *
     * @throws NotFoundHttpException
     *
     * @return JsonResponse
     */
    public function findAction(Request $request)
    {
        $term = $request->get('term');
        $searchData = [
            'forename' => $term,
            'surname' => $term,
            'username' => $term,
            'email' => $term
        ];

        $filters = $this->filterCollectionBuilder->buildFromArray(
            $searchData,
            new UserFilterMapper(),
            FilterCollection::JOIN_TYPE_OR
        );

        $users = $this->userRepository->findByFilters($filters);
        $decorator = $this->baseHelper->getObjectCollectionDecorator();
        $decorator->setPropertyMappings(['fullName' => 'text']);
        $users = $decorator->decorate($users->getIterator(), ['id', 'fullName', 'email']);

        return new JsonResponse($users);
    }
}
