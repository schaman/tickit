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

    /**
     * Serializes an iterable.
     *
     * The iterable should contain serializable objects.
     *
     * Objects should be serialized by reference, so the original data
     * structure will be returned with serialized contents.
     *
     * @param array|\Iterator $iterable An iterable to serialize
     *
     * @return mixed
     */
    public function serializeIterable($iterable);
}
