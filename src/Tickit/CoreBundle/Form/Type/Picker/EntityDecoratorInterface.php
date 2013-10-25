<?php

namespace Tickit\CoreBundle\Form\Type\Picker;

/**
 * Entity decorator interfaces.
 *
 * Entity decorators are responsible for decorating an entity
 * instance into a scalar representation for form picker views.
 *
 * @package Tickit\CoreBundle\Form\Type\Picker
 */
interface EntityDecoratorInterface
{
    /**
     * Decorates an entity with a scalar representation
     *
     * @param mixed $entity The entity to decorate
     *
     * @return mixed The decorated value
     */
    public function decorate($entity);
}
