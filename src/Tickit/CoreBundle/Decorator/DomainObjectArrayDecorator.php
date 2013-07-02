<?php

namespace Tickit\CoreBundle\Decorator;

/**
 * Array decorator for domain objects.
 *
 * @package Tickit\CoreBundle\Decorator
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class DomainObjectArrayDecorator implements DomainObjectDecoratorInterface
{
    /**
     * Decorates an object as an array using the specified property names
     *
     * @param object $object       The domain object to decorate
     * @param array $propertyNames The property names used in the decoration
     *
     * @throws \InvalidArgumentException If the $object property is not an object
     * @throws \RuntimeException         If the any of the provided properties do not have a getter
     *
     * @return string
     */
    public function decorate($object, $propertyNames)
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
