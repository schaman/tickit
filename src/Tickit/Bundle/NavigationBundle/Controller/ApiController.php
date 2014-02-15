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

namespace Tickit\Bundle\NavigationBundle\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Tickit\Component\Navigation\Builder\NavigationBuilder;
use Tickit\Component\Navigation\Model\NavigationItem;

/**
 * Provides actions related to the application navigation
 *
 * @package Tickit\Bundle\NavigationBundle\Controller
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
     * @param string $name The name of the navigation to build
     *
     * @return JsonResponse
     */
    public function navItemsAction($name = 'main')
    {
        $items = $this->navigationBuilder->build($name);
        $data = array();
        $items->top();

        foreach ($items as $navItem) {
            $data[] = $navItem;
        }

        return new JsonResponse($data);
    }
}
