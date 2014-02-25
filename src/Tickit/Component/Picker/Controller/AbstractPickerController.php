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

namespace Tickit\Component\Picker\Controller;

use Tickit\Component\Controller\Helper\BaseHelper;
use Tickit\Component\Filter\Collection\Builder\FilterCollectionBuilder;
use Tickit\Component\Filter\Repository\FilterableRepositoryInterface;

/**
 * Abstract picker controller.
 *
 * Base implementation of a picker controller
 *
 * @package Tickit\Component\Picker\Controller
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
abstract class AbstractPickerController
{
    /**
     * A filterable repository
     *
     * @var FilterableRepositoryInterface
     */
    protected $repository;

    /**
     * Filter collection builder
     *
     * @var FilterCollectionBuilder
     */
    protected $filterCollectionBuilder;

    /**
     * The base controller helper
     *
     * @var BaseHelper
     */
    protected $baseHelper;

    /**
     * Constructor.
     *
     * @param FilterableRepositoryInterface $repository              A filterable repository
     * @param BaseHelper                    $baseHelper              The base controller helper
     * @param FilterCollectionBuilder       $filterCollectionBuilder The filter collection builder
     */
    public function __construct(
        FilterableRepositoryInterface $repository,
        BaseHelper $baseHelper,
        FilterCollectionBuilder $filterCollectionBuilder
    ) {
        $this->repository               = $repository;
        $this->filterCollectionBuilder  = $filterCollectionBuilder;
        $this->baseHelper               = $baseHelper;
    }
}
