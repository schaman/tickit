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
     * @throws TransformationFailedException If there are more values than $this->maxSelections
     *
     * @return mixed The value in the transformed representation
     */
    public function transform($value)
    {
        if (null === $value) {
            return '';
        }

        if (!$value instanceof Collection) {
            $value = new ArrayCollection([$value]);
        }

        if ($this->hasSelectionsLimit() && $this->maxSelections < $value->count()) {
            throw new TransformationFailedException(
                sprintf(
                    'Too many values provided to the form field (Picker). Expected maximum %d, got %d',
                    $this->maxSelections,
                    $value->count()
                )
            );
        }

        $flattened = array_map(
            function ($entity) {
                return $this->transformEntityToSimpleObject($entity);
            },
            $value->toArray()
        );

        return json_encode(array_values($flattened));
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
     * Returns true if the transformer has a max selections limit set
     *
     * @return boolean
     */
    private function hasSelectionsLimit()
    {
        return $this->maxSelections !== null;
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
     *  Transforms a given entity instance into a simple object.
     *
     * This allows the data transformer to encode it to a scalar
     *
     * @param mixed $entity The entity instance to transform
     *
     * @return string
     */
    abstract protected function transformEntityToSimpleObject($entity);

    /**
     * Returns an entity instance by identifier
     *
     * @param mixed $identifier The entity identifier value
     *
     * @return mixed
     */
    abstract protected function findEntityByIdentifier($identifier);
}
