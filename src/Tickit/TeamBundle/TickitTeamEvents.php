<?php

namespace Tickit\TeamBundle;

/**
 * TeamBundle events collection.
 *
 * This class contains a collection of constants representing event names for the team bundle
 *
 * @package Tickit\TeamBundle
 * @author  James Halsall <jhalsall@rippleffect.com>
 */
class TickitTeamEvents
{
    /**
     * Constant representing the name of the "before create" event
     *
     * @const string
     */
    const TEAM_BEFORE_CREATE = 'tickit_team.event.before_create';

    /**
     * Constant representing the name of the "create" event
     *
     * @const string
     */
    const TEAM_CREATE = 'tickit_team.event.create';

    /**
     * Constant representing the name of the "before update" event
     *
     * @const string
     */
    const TEAM_BEFORE_UPDATE = 'tickit_team.event.before_update';

    /**
     * Constant representing the name of the "update" event
     *
     * @const string
     */
    const TEAM_UPDATE = 'tickit_team.event.update';

    /**
     * Constant representing the name of the "before delete" event
     *
     * @const string
     */
    const TEAM_BEFORE_DELETE = 'tickit_team.event.before_delete';

    /**
     * Constant representing the name of the "delete" event
     *
     * @const string
     */
    const TEAM_DELETE = 'tickit_team.event.delete';
}
