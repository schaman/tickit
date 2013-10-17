<?php

namespace Tickit\CoreBundle\Decorator\Collection;

/**
 * File description
 *
 * @package Tickit\CoreBundle\Decorator\Collection
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
interface DomainObjectCollectionDecoratorInterface
{
    /**
     * Decorates a collection of domain objects and returns the result.
     *
     * @param array $data             The domain objects to be decorated
     * @param array $propertyNames    Property names of the domain objects to include in the decorated result
     * @param array $staticProperties The static properties to attach to each decorated result
     *
     * @return array
     */
    public function decorate(array $data, array $propertyNames, array $staticProperties = array());
}
