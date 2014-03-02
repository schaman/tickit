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
use Symfony\Component\HttpFoundation\Request;
use Tickit\Component\Filter\Collection\FilterCollection;
use Tickit\Component\Filter\Map\Project\ProjectFilterMapper;
use Tickit\Component\Picker\Controller\AbstractPickerController;

/**
 * Picker controller for projects.
 *
 * Serves picker related actions for the project bundle.
 *
 * @package Tickit\Bundle\ProjectBundle\Controller
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class PickerController extends AbstractPickerController
{
    /**
     * Find action.
     *
     * Finds matches for the picker search term.
     *
     * Returns the results in a JSON object
     *
     * @param Request $request The request object
     *
     * @return JsonResponse
     */
    public function findAction(Request $request)
    {
        $term = $request->get('term');
        $searchData = ['name' => $term];

        $filters = $this->filterCollectionBuilder->buildFromArray(
            $searchData,
            new ProjectFilterMapper(),
            FilterCollection::JOIN_TYPE_OR
        );

        $projects = $this->repository->findByFilters($filters);
        $decorator = $this->baseHelper->getObjectCollectionDecorator();
        $decorator->setPropertyMappings(['name' => 'text']);
        $projects = $decorator->decorate($projects->getIterator(), ['id', 'name']);

        return new JsonResponse($projects);
    }
}
