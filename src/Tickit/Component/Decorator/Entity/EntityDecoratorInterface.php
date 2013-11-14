<?php

namespace Tickit\Component\Decorator\Entity;

/**
 * Entity decorator interfaces.
 *
 * Entity decorators are responsible for decorating an entity
 * instance into a scalar representation for form picker views.
 *
 * @package Tickit\Component\Decorator\Entity
 */
interface EntityDecoratorInterface
{
    /**
     * Decorates an entity with a scalar representation
     *
     * @param mixed $entity The entity to decorate
     *
     * @throws \InvalidArgumentException If the $entity argument isn't the correct instance
     *
     * @return mixed The decorated value
     */
    public function decorate($entity);
}
