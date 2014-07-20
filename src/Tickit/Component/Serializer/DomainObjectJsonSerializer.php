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
