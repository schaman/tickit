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

namespace Tickit\NavigationBundle\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Tickit\NavigationBundle\Builder\NavigationBuilder;
use Tickit\NavigationBundle\Model\NavigationItem;

/**
 * Provides actions related to the application navigation
 *
 * @package Tickit\NavigationBundle\Controller
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class ApiController
{
    /**
     * The navigation builder
     *
     * @var NavigationBuilder
     */
    private $navigationBuilder;

    /**
     * Constructor.
     *
     * @param NavigationBuilder $builder The navigation builder
     */
    public function __construct(NavigationBuilder $builder)
    {
        $this->navigationBuilder = $builder;
    }

    /**
     * Lists available navigation items for the currently authenticated user
     *
     * @return JsonResponse
     */
    public function navItemsAction()
    {
        $items = $this->navigationBuilder->build();

        $data = array();
        $items->top();
        /** @var NavigationItem $navItem */
        foreach ($items as $navItem) {
            // When we move to PHP 5.4 we can make NavigationItem JsonSerializable to avoid having
            // the builder responsible for constructing the Json representation
            $data[] = array(
                'name' => $navItem->getText(),
                'routeName' => $navItem->getRouteName(),
                'active' => false
            );
        }

        return new JsonResponse($data);
    }
}
