<?php

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
