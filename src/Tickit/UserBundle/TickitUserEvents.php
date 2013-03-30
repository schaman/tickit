<?php

namespace Tickit\UserBundle;

/**
 * UserBundle events collection.
 *
 * This class contains a collection of constants representing event names for the user bundle
 *
 * @package Tickit\UserBundle
 * @author  James Halsall <jhalsall@rippleffect.com>
 */
class TickitUserEvents
{
    /**
     * Constant representing the name of the "before create" event
     *
     * @const string
     */
    const USER_BEFORE_CREATE = 'tickit_user.event.before_create';

    /**
     * Constant representing the name of the "create" event
     *
     * @const string
     */
    const USER_CREATE = 'tickit_user.event.create';

    /**
     * Constant representing the name of the "before update" event
     *
     * @const string
     */
    const USER_BEFORE_UPDATE = 'tickit_user.event.before_update';

    /**
     * Constant representing the name of the "update" event
     *
     * @const string
     */
    const USER_UPDATE = 'tickit_user.event.update';

    /**
     * Constant representing the name of the "before delete" event
     *
     * @const string
     */
    const USER_BEFORE_DELETE = 'tickit_user.event.before_delete';

    /**
     * Constant representing the name of the "delete" event
     *
     * @const string
     */
    const USER_DELETE = 'tickit_user.event.delete';
}
