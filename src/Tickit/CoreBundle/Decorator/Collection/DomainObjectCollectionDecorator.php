<?php

/*
 * Tickit, an source web based bug management tool.
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

namespace Tickit\CoreBundle\Decorator\Collection;

use Tickit\CoreBundle\Decorator\DomainObjectDecoratorInterface;

/**
 * Domain object collection array decorator.
 *
 * Responsible for decorating a collection of domain objects as arrays
 *
 * @package Tickit\CoreBundle\Decorator\Collection
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
     * @param array $data             The domain objects to be decorated
     * @param array $propertyNames    Property names of the domain objects to include in the decorated result
     * @param array $staticProperties The static properties to attach to each decorated result
     *
     * @return array
     */
    public function decorate(array $data, array $propertyNames, array $staticProperties = array())
    {
        $decorated = [];

        foreach ($data as $object) {
            $decorated[] = $this->decorator->decorate($object, $propertyNames, $staticProperties);
        }

        return $decorated;
    }
}
