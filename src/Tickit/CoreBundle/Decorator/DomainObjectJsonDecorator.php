<?php

namespace Tickit\CoreBundle\Decorator;

/**
 * JSON decorator for domain objects.
 *
 * @package Tickit\CoreBundle\Decorator
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class DomainObjectJsonDecorator implements DomainObjectDecoratorInterface
{
    /**
     * Decorates an object as a JSON string using the specified property names
     *
     * @param object $object        The domain object to decorate
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

        $data = array();
        foreach ($propertyNames as $property) {
            $getter = sprintf('get%s', ucfirst($property));

            if (!method_exists($object, $getter)) {
                throw new \RuntimeException(
                    sprintf('The property %s does not have a getter on the provided object', $getter)
                );
            }

            $data[] = $object->{$getter}();
        }

        return json_encode($data);
    }
}