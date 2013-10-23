<?php

namespace Tickit\ProjectBundle\Form\Event;

use Symfony\Component\EventDispatcher\Event;

/**
 * Event for the entity attribute form.
 *
 * This event is dispatched when the entity form is about to be built.
 *
 * The purpose of this event is to provide other bundles with the ability to make their
 * entities available for selection in the EntityAttributeFormType.
 *
 * @package Tickit\ProjectBundle\Form\Event
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class EntityAttributeFormBuildEvent extends Event
{
    /**
     * Current entity choices
     *
     * @var array
     */
    protected $entityChoices = array();

    /**
     * Adds an entity choice.
     *
     * @param string $className The class name of the entity
     * @param string $name      The display name for the entity
     */
    public function addEntityChoice($className, $name)
    {
        $this->entityChoices[$className] = $name;
    }

    /**
     * Gets current entity choices
     *
     * @return array
     */
    public function getEntityChoices()
    {
        return $this->entityChoices;
    }
}
