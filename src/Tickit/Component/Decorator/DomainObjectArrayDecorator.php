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

namespace Tickit\Component\Decorator;

/**
 * Array decorator for domain objects.
 *
 * @package Tickit\Component\Decorator
 * @author  James Halsall <james.t.halsall@googlemail.com>
 * @author  Mark Wilson <mark@89allport.co.uk>
 */
class DomainObjectArrayDecorator implements DomainObjectDecoratorInterface
{
    /**
     * Decorates an object as an array using the specified property names.
     *
     * The $staticProperties parameter allows for the injection of additional property names and
     * values that will be appended to the resulting array. This is useful for things like CSRF tokens
     * which are static against each domain object
     *
     * @param object $object          The domain object to decorate
     * @param array $propertyNames    The property names used in the decoration
     * @param array $staticProperties Any additional static properties that should be appended to the result
     *                                (indexed by property name)
     *
     * @throws \InvalidArgumentException If the $object property is not an object
     * @throws \RuntimeException         If the any of the provided properties do not have a getter
     *
     * @return string
     */
    public function decorate($object, array $propertyNames, array $staticProperties = array())
    {
        if (!is_object($object)) {
            throw new \InvalidArgumentException('You must provide a valid domain object to decorate');
        }

        // initialise data array output
        $data = array();
        foreach ($propertyNames as $property) {
            $match = false;
            $value = false;

            // find the heirarchy of properties being accessed
            $heirarchy         = explode('.', $property);
            $heirarchyIterator = new \ArrayIterator($heirarchy);

            // initialise a current object
            $currentObject = $object;
            // loop through the heirarchy
            while ($heirarchyIterator->valid()) {
                // current property name
                $currentNode = $heirarchyIterator->current();
                $accessors   = $this->guessAccessorNames($currentNode);

                // try each potential accessor method for this property
                foreach ($accessors as $accessor) {
                    // if the method exists on current object
                    if (true === method_exists($currentObject, $accessor)) {
                        $match = true;

                        // execute accessor method
                        $value = $currentObject->{$accessor}();

                        // no need to test for any further accessor methods
                        break;
                    }
                }

                // no match found
                if (false === $match) {
                    throw new \RuntimeException(
                        sprintf('The property %s does not have a getter on the provided object', $property)
                    );
                }

                // update the current object to the value - only relevant if there are no more nodes in heirarchy
                $currentObject = $value;

                // move to next node
                $heirarchyIterator->next();
            }

            // if value is a date instance, change to string
            if ($value instanceof \DateTime) {
                $value = $value->format('Y-m-d H:i:s');
            }

            // append property to data array
            $data[$property] = $value;
        }

        // merge any static properties into the data array
        $data = array_merge($data, $staticProperties);

        return $data;
    }

    /**
     * Guesses a bunch of field accessor method names based off a property name
     *
     * @param string $propertyName The property name to guess accessors for
     *
     * @return array
     */
    protected function guessAccessorNames($propertyName)
    {
        $guesses = array();
        $accessorPrefixes = array('get', 'is', '');
        foreach ($accessorPrefixes as $prefix) {
            $guesses[] = sprintf('%s%s', $prefix, ucfirst($propertyName));
        }

        return $guesses;
    }
}
