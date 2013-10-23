<?php

namespace Tickit\CoreBundle\Form\Type\Picker;

/**
 * Entity converter interface.
 *
 * Entity converters are responsible for converting an entity instance or an
 * entity identifier into a description scalar value that is used in a form's view.
 *
 * @package Tickit\CoreBundle\Form\Type
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
interface EntityConverterInterface
{
    /**
     * Converts an entity or entity representation into a descriptive scalar representation
     * for form use.
     *
     * The method should throw an EntityNotFoundException in the event that an entity is invalid
     * or cannot be found.
     *
     * @param mixed $entity The entity to convert
     *
     * @throws \Doctrine\ORM\EntityNotFoundException
     *
     * @return mixed
     */
    public function convert($entity);
}
