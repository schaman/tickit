<?php

namespace Tickit\ProjectBundle;

/**
 * ProjectBundle events collection.
 *
 * This class contains a collection of constants representing event names for the project bundle
 *
 * @package Tickit\ProjectBundle
 * @author  James Halsall <jhalsall@rippleffect.com>
 */
class TickitProjectEvents
{
    /**
     * Constant representing the name of the create event
     *
     * @const string
     */
    const PROJECT_CREATE = 'tickit_project.event.create';

    /**
     * Constant representing the name of the update event
     *
     * @const string
     */
    const PROJECT_UPDATE = 'tickit_project.event.update';

    /**
     * Constant representing the name of the delete event
     *
     * @const string
     */
    const PROJECT_DELETE = 'tickit_project.event.delete';
}
