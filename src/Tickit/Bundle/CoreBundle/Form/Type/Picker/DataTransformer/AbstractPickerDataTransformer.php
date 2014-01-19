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

namespace Tickit\Bundle\CoreBundle\Form\Type\Picker\DataTransformer;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

/**
 * Abstract implementation of picker data transformer.
 *
 * The picker data transformer is responsible for transforming
 * entity data (collections, entities etc) into a scalar format
 * and vice versa.
 *
 * @package Tickit\Bundle\CoreBundle\Form\Type\Picker\DataTransformer
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
abstract class AbstractPickerDataTransformer implements DataTransformerInterface
{
    /**
     * The restriction type on the picker that this transformer is attached to
     *
     * @var integer|null
     */
    private $maxSelections;

    /**
     * Sets the maximum number of selections allowed
     *
     * @param integer $maxSelections The maximum number of selections allowed
     */
    public function setMaxSelections($maxSelections)
    {
        if (intval($maxSelections) === 0) {
            $this->maxSelections = null;
        } else {
            $this->maxSelections = intval($maxSelections);
        }
    }

    /**
     * Gets the maximum number of selections allowed
     *
     * @return integer
     */
    public function getMaxSelections()
    {
        return $this->maxSelections;
    }

    /**
     * Transforms a value from the original representation to a transformed representation.
     *
     * By convention, transform() should return an empty string if NULL is
     * passed.
     *
     * @param mixed $value The value in the original representation
     *
     * @return mixed The value in the transformed representation
     */
    public function transform($value)
    {
        if (null === $value) {
            return '';
        }

        if ($this->maxSelections === 1) {
            return $this->transformEntity($value);
        } else {
            return $this->transformCollection($value);
        }
    }

    /**
     * Transforms a value from the transformed representation to its original
     * representation.
     *
     * This method is called when {@link Form::submit()} is called to transform the requests tainted data
     * into an acceptable format for your data processing/model layer.
     *
     * By convention, reverseTransform() should return NULL if an empty string
     * is passed.
     *
     * @param mixed $value The value in the transformed representation
     *
     * @return mixed The value in the original representation
     *
     * @throws TransformationFailedException When the transformation fails.
     */
    public function reverseTransform($value)
    {
        if ('' === $value) {
            return null;
        }

        if ($this->maxSelections === 1) {
            return $this->reverseTransformSingleIdentifier($value);
        } else {
            return $this->reverseTransformMultipleIdentifiers($value);
        }
    }

    /**
     * Transforms a single entity instance
     *
     * @param mixed $entity The entity to transform
     *
     * @throws TransformationFailedException If the provided $entity is actually a Collection instance
     *
     * @return mixed
     */
    private function transformEntity($entity)
    {
        if ($entity instanceof Collection) {
            throw new TransformationFailedException();
        }

        $method = $this->getIdentifierAccessorName();
        $this->validateMethodExists($entity, $method);

        return $entity->{$method}();
    }

    /**
     * Transforms a collection of entity instances
     *
     * @param Collection $collection The collection to transform
     *
     * @throws TransformationFailedException If the $collection argument is not actually a collection
     *
     * @return string
     */
    private function transformCollection($collection)
    {
        if (!$collection instanceof Collection) {
            throw new TransformationFailedException(
                'A Collection instance was not provided when trying to transform multiple entities'
            );
        }

        $flattened = array_map(
            function ($entity) {
                return $this->transformEntity($entity);
            },
            $collection->toArray()
        );

        return implode(',', $flattened);
    }

    /**
     * Reverse transforms a single identifier into an entity
     *
     * @param string|integer $identifier The identifier
     *
     * @throws TransformationFailedException If the $identifier is a string of more than one identifier
     *
     * @return mixed
     */
    private function reverseTransformSingleIdentifier($identifier)
    {
        $identifier = explode(',', $identifier);
        if (count($identifier) > 1) {
            throw new TransformationFailedException(
                'More than one identifier was provided to reverseTransform() with single select restriction enabled'
            );
        }

        return $this->findEntityByIdentifier($identifier[0]);
    }

    /**
     * Reverse transforms a string of comma separated identifiers into a collection of entities
     *
     * @param string $identifiers The string of identifiers
     *
     * @return ArrayCollection
     */
    private function reverseTransformMultipleIdentifiers($identifiers)
    {
        $entities = [];
        $values = explode(',', $identifiers);
        foreach ($values as $identifier) {
            $entity = $this->findEntityByIdentifier($identifier);
            if (null === $entity) {
                continue;
            }
            $entities[] = $entity;
        }

        return new ArrayCollection($entities);
    }

    /**
     * @return string
     */
    private function getIdentifierAccessorName()
    {
        $identifier = $this->getEntityIdentifier();
        return "get" . ucfirst($identifier);
    }

    /**
     * Validates that a method exists on an object.
     *
     * @param mixed  $object The object to check
     * @param string $method The method name to check
     *
     * @throws \RuntimeException If the method does not exist
     */
    private function validateMethodExists($object, $method)
    {
        if (!method_exists($object, $method)) {
            throw new \RuntimeException(
                sprintf(
                    'The method %s() does not exist and/or is not public on %s',
                    $method,
                    get_class($object)
                )
            );
        }
    }

    /**
     * Returns the name of the entity identifier.
     *
     * @return string
     */
    abstract protected function getEntityIdentifier();

    /**
     * Returns an entity instance by identifier
     *
     * @param mixed $identifier The entity identifier value
     *
     * @return mixed
     */
    abstract protected function findEntityByIdentifier($identifier);
}
