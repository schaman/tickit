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

namespace Tickit\Component\Form\DataTransformer;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\PropertyAccess\PropertyAccessor;

/**
 * EntityToEntityIdDataTransformer
 *
 * @package Tickit\Component\Form\DataTransformer
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class EntityToEntityIdDataTransformer implements DataTransformerInterface
{
    /**
     * An entity manager
     *
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * The FQ class name of the entity being transformed
     *
     * @var string
     */
    private $entityClassName;

    /**
     * The property used to transform the object to/from a string representation
     *
     * @var string
     */
    private $transformProperty;

    /**
     * Constructor.
     *
     * @param EntityManagerInterface $em                An entity manager
     * @param string                 $entityClassName   The FQ class name of the entity being transformed
     * @param string                 $transformProperty The property used to transform the object to/from a string
     *                                                  representation
     */
    public function __construct(EntityManagerInterface $em, $entityClassName, $transformProperty)
    {
        $this->em = $em;
        $this->entityClassName = $entityClassName;
        $this->transformProperty = $transformProperty;
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
     *
     * @throws TransformationFailedException When the transformation fails.
     */
    public function transform($value)
    {
        if (null === $value) {
            return '';
        }

        if (get_class($value) !== $this->entityClassName) {
            throw new TransformationFailedException(
                sprintf('The value provided does match the expected class name (%s)', $this->entityClassName)
            );
        }

        $propertyAccessor = new PropertyAccessor();

        return $propertyAccessor->getValue($value, $this->transformProperty);
    }

    /**
     * Transforms a value from the transformed representation to its original
     * representation.
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
        if (null === $value) {
            return null;
        }

        $entity = $this->em->getRepository($this->entityClassName)
                           ->findOneBy([$this->transformProperty => $value]);

        if (get_class($entity) !== $this->entityClassName) {
            throw new TransformationFailedException(
                sprintf(
                    'No entity of class "%s" could be found with a "%s" value of "%s"',
                    $this->entityClassName,
                    $this->transformProperty,
                    $value
                )
            );
        }

        return $entity;
    }
}
