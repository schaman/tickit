<?php

namespace Tickit\ClientBundle;

/**
 * Tickit client bundle events.
 *
 * Contains static event names.
 *
 * @package Tickit\ClientBundle
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
final class TickitClientEvents
{
    /**
     * Constant representing the name of the "before create" event
     *
     * @const string
     */
    const CLIENT_BEFORE_CREATE = 'tickit_client.event.before_create';

    /**
     * Constant representing the name of the "create" event
     *
     * @const string
     */
    const CLIENT_CREATE = 'tickit_client.event.create';

    /**
     * Constant representing the name of the "before update" event
     *
     * @const string
     */
    const CLIENT_BEFORE_UPDATE = 'tickit_client.event.before_update';

    /**
     * Constant representing the name of the "update" event
     *
     * @const string
     */
    const CLIENT_UPDATE = 'tickit_client.event.update';

    /**
     * Constant representing the name of the "before delete" event
     *
     * @const string
     */
    const CLIENT_BEFORE_DELETE = 'tickit_client.event.before_delete';

    /**
     * Constant representing the name of the "delete" event
     *
     * @const string
     */
    const CLIENT_DELETE = 'tickit_client.event.delete';
}
