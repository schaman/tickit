<?php

namespace Tickit\UserBundle\Form\EventListener;

use Tickit\ProjectBundle\Form\Event\EntityAttributeFormBuildEvent;

/**
 * UserBundle form event listener.
 *
 * Responsible for hooking into form related events.
 *
 * @package Tickit\UserBundle\Form\EventListener
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class FormSubscriber
{
    /**
     * Hooks into the TickitProjectBundleEvents::ENTITY_ATTRIBUTE_FORM_BUILD
     *
     * The purpose of this event handler is to register any entities in the user bundle
     * that should be available as choices in the EntityAttribute forms.
     *
     * @param EntityAttributeFormBuildEvent $event The event
     *
     * @return void
     */
    public function onEntityAttributeFormBuild(EntityAttributeFormBuildEvent $event)
    {
        $event->addEntityChoice('Tickit\UserBundle\Entity\User', 'User');
    }
}
