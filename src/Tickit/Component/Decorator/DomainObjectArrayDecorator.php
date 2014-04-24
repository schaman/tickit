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
     * Custom property mappings.
     *
     * Used to output different property names than were originally on
     * the undecorated object.
     *
     * @var array
     */
    private $propertyMappings = [];

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
     * @return array
     */
    public function decorate($object, array $propertyNames, array $staticProperties = [])
    {
        if (!is_object($object)) {
            throw new \InvalidArgumentException('You must provide a valid domain object to decorate');
        }

        // initialise data array output
        $data = array();
        foreach ($propertyNames as $property) {
            $match = false;
            $value = false;

            // find the hierarchy of properties being accessed
            $hierarchy = explode('.', $property);
            $hierarchy = new \ArrayIterator($hierarchy);

            // initialise a current object
            $currentObject = $object;
            // loop through the heirarchy
            while ($hierarchy->valid()) {
                // current property name
                $currentNode = $hierarchy->current();
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

                // update the current object to the value - only relevant if there are no more nodes in hierarchy
                $currentObject = $value;

                // move to next node
                $hierarchy->next();
            }

            // if value is a date instance, change to string
            if ($value instanceof \DateTime) {
                $value = $value->format('Y-m-d H:i:s');
            }

            // append property to data array
            $this->addValueToArray($data, $property, $value);
        }

        // merge any static properties into the data array
        $data = array_merge($data, $staticProperties);

        return $data;
    }

    /**
     * Sets any field mappings.
     *
     * This is used to transform a property on the object to appear as a different property in
     * the decorated output. Can be useful for masking original object property names.
     *
     * @param array $propertyMappings An array of custom field names which are used to override the real field names in the
     *                             decorated output. Indexed by original field name
     * @return mixed
     */
    public function setPropertyMappings(array $propertyMappings = [])
    {
        $this->propertyMappings = $propertyMappings;
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

    /**
     * @param array  $data         A reference to the data array
     * @param string $propertyName The property name of the value to add
     * @param mixed  $value        The value to add to the array
     */
    private function addValueToArray(&$data, $propertyName, $value)
    {
        if (isset($this->propertyMappings[$propertyName])) {
            $propertyName = $this->propertyMappings[$propertyName];
        }

        $data[$propertyName] = $this->flattenValue($value);
    }

    /**
     * Attempts to flatten values into a multi-dimensional array.
     *
     * Relies on complex objects to implement the \JsonSerializable interface.
     *
     * @param mixed $value A value that potentially needs flattening.
     *
     * @return mixed
     */
    private function flattenValue($value)
    {
        if (true === is_object($value) && $value instanceof \JsonSerializable) {
            $value = $value->jsonSerialize();
            if (true === is_array($value)) {
                foreach ($value as $key => $v) {
                    $value[$key] = $this->flattenValue($v);
                }
            }
        }

        return $value;
    }
}
