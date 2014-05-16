<?php

namespace Tickit\Component\Serializer;

use JMS\Serializer\SerializerInterface as JmsSerializerInterface;

/**
 * DomainObjectSerializer
 *
 * @package Tickit\Component\Serializer
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class DomainObjectJsonSerializer implements SerializerInterface
{
    /**
     * A JMS serializer
     *
     * @var JmsSerializerInterface
     */
    private $serializer;

    /**
     * Constructor.
     *
     * @param JmsSerializerInterface $serializer A JMS serializer
     */
    public function __construct(JmsSerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * Serializes an object
     *
     * @param mixed $object The object to serialize
     *
     * @return string
     */
    public function serialize($object)
    {
        return $this->serializer->serialize($object, 'json');
    }

    /**
     * Serializes an iterable.
     *
     * The iterable should contain serializable objects.
     *
     * @param array|\Iterator $iterable An iterable to serialize
     *
     * @return mixed
     */
    public function serializeIterable($iterable)
    {
        foreach ($iterable as &$object) {
            $object = $this->serializer->serialize($object, 'json');
        }

        return $iterable;
    }
}
