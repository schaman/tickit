<?php

namespace Tickit\Component\Serializer;

/**
 * Serializer interface.
 *
 * @package Tickit\Component\Serializer
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
interface SerializerInterface
{
    /**
     * Serializes an object
     *
     * @param mixed $object The object to serialize
     *
     * @return string
     */
    public function serialize($object);
}
