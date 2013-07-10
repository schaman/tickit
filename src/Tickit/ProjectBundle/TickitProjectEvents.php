<?php

namespace Tickit\ProjectBundle;

/**
 * ProjectBundle events collection.
 *
 * This class contains a collection of constants representing event names for the project bundle
 *
 * @package Tickit\ProjectBundle
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class TickitProjectEvents
{
    /**
     * Constant representing the name of the "before create" event
     *
     * @const string
     */
    const PROJECT_BEFORE_CREATE = 'tickit_project.event.before_create';

    /**
     * Constant representing the name of the "create" event
     *
     * @const string
     */
    const PROJECT_CREATE = 'tickit_project.event.create';

    /**
     * Constant representing the name of the "before update" event
     *
     * @const string
     */
    const PROJECT_BEFORE_UPDATE = 'tickit_project.event.before_update';

    /**
     * Constant representing the name of the "update" event
     *
     * @const string
     */
    const PROJECT_UPDATE = 'tickit_project.event.update';

    /**
     * Constant representing the name of the "before delete" event
     *
     * @const string
     */
    const PROJECT_BEFORE_DELETE = 'tickit_project.event.before_delete';

    /**
     * Constant representing the name of the "delete" event
     *
     * @const string
     */
    const PROJECT_DELETE = 'tickit_project.event.delete';
}
