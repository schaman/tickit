<?php

namespace Tickit\CoreBundle\Decorator;

/**
 * Domain object decorator interface.
 *
 * Domain object decorators are responsible for taking a domain object and
 * decorating it using a specific format.
 *
 * @package Tickit\CoreBundle\Decorator
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
interface DomainObjectDecoratorInterface
{
    /**
     * Decorates an object as a JSON string using the specified property names
     *
     * @param object $object           The domain object to decorate
     * @param array  $propertyNames    The property names used in the decoration
     * @param array  $staticProperties Any additional static properties that should be appended to each result (indexed by property name)
     *
     * @return string
     */
    public function decorate($object, array $propertyNames, array $staticProperties = array());
}
