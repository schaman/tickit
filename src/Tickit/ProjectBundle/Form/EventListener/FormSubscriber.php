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

namespace Tickit\ProjectBundle\Form\EventListener;

use Tickit\ProjectBundle\Form\Event\EntityAttributeFormBuildEvent;

/**
 * ProjectBundle form event listener.
 *
 * Responsible for hooking into form related events.
 *
 * @package Tickit\ProjectBundle\Form\EventListener
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class FormSubscriber
{
    /**
     * Hooks into the TickitProjectBundleEvents::ENTITY_ATTRIBUTE_FORM_BUILD
     *
     * The purpose of this event handler is to register any entities in the project bundle
     * that should be available as choices in the EntityAttribute forms.
     *
     * @param EntityAttributeFormBuildEvent $event The event
     *
     * @return void
     */
    public function onEntityAttributeFormBuild(EntityAttributeFormBuildEvent $event)
    {
        $event->addEntityChoice('Tickit\ProjectBundle\Entity\Project', 'Project');
    }
}
