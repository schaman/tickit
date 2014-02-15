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

namespace Tickit\Component\Decorator\Collection;

use Tickit\Component\Decorator\DomainObjectDecoratorInterface;

/**
 * Domain object collection array decorator.
 *
 * Responsible for decorating a collection of domain objects as arrays
 *
 * @package Tickit\Component\Decorator\Collection
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class DomainObjectCollectionDecorator implements DomainObjectCollectionDecoratorInterface
{
    /**
     * A domain object decorator
     *
     * @var DomainObjectDecoratorInterface
     */
    private $decorator;

    /**
     * Custom property mappings.
     *
     * @var array
     */
    private $propertyMappings = [];

    /**
     * Constructor.
     *
     * @param DomainObjectDecoratorInterface $decorator A domain object decorator
     */
    public function __construct(DomainObjectDecoratorInterface $decorator)
    {
        $this->decorator = $decorator;
    }

    /**
     * Decorates a collection of domain objects and returns the result.
     *
     * @param array|\ArrayIterator    $data             The domain objects to be decorated
     * @param array                   $propertyNames    Property names of the domain objects to include in the decorated
     *                                                  result
     * @param array                   $staticProperties The static properties to attach to each decorated result
     *
     * @return array
     */
    public function decorate($data, array $propertyNames, array $staticProperties = [])
    {
        $this->decorator->setPropertyMappings($this->propertyMappings);
        $decorated = [];

        if (!$data instanceof \ArrayIterator) {
            $data = new \ArrayIterator($data);
        }

        while ($data->valid()) {
            $object = $data->current();
            $decorated[] = $this->decorator->decorate($object, $propertyNames, $staticProperties);
            $data->next();
        }

        return $decorated;
    }

    /**
     * Sets any field mappings.
     *
     * This is used to transform a property names in the object collection to appear as a
     * different properties in the decorated output. Can be useful for masking original
     * object property names.
     *
     * @param array $propertyMappings An array of custom property names which are used to override the real property
     *                                names in the decorated output. New properties should be indexed by the original
     *                                property name, with the values being the new property names
     * @return mixed
     */
    public function setPropertyMappings(array $propertyMappings = [])
    {
        $this->propertyMappings = $propertyMappings;
    }
}
