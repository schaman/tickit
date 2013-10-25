<?php

namespace Tickit\CoreBundle\Form\Type\Picker\DataTransformer;

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
 * @package Tickit\CoreBundle\Form\Type\Picker\DataTransformer
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
abstract class AbstractPickerDataTransformer implements DataTransformerInterface
{
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

        if (!$value instanceof Collection) {
            if (false === is_array($value)) {
                $value = [$value];
            }
            $value = new ArrayCollection($value);
        }

        $identifier = $this->getEntityIdentifier();
        $method = "get" . ucfirst($identifier);
        $flattened = array_map(
            function ($entity) use ($method) {
                if (!method_exists($entity, $method)) {
                    throw new \RuntimeException(
                        sprintf(
                            'The method %s() does not exist and/or is not public on %s',
                            $method,
                            get_class($entity)
                        )
                    );
                }
                return $entity->{$method}();
            },
            $value->toArray()
        );

        return implode(',', $flattened);
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

        $entities = [];
        $values = explode(',', $value);
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
 