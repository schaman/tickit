<?php

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
