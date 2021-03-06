<?php

/*
 * Tickit, an open source web based bug management tool.
 *
 * Copyright (C) 2014  Tickit Project <http://tickit.io>
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

namespace Tickit\Component\Event\Issue;

/**
 * Issue model events.
 *
 * Contains static event names for Issue related model events.
 *
 * @package Tickit\Component\Event\Issue
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
final class IssueEvents
{
    /**
     * Constant representing the name of the "before create" event
     *
     * @const string
     */
    const ISSUE_BEFORE_CREATE = 'tickit_issue.event.before_create';

    /**
     * Constant representing the name of the "create" event
     *
     * @const string
     */
    const ISSUE_CREATE = 'tickit_issue.event.create';

    /**
     * Constant representing the name of the "before update" event
     *
     * @const string
     */
    const ISSUE_BEFORE_UPDATE = 'tickit_issue.event.before_update';

    /**
     * Constant representing the name of the "update" event
     *
     * @const string
     */
    const ISSUE_UPDATE = 'tickit_issue.event.update';

    /**
     * Constant representing the name of the "before delete" event
     *
     * @const string
     */
    const ISSUE_BEFORE_DELETE = 'tickit_issue.event.before_delete';

    /**
     * Constant representing the name of the "delete" event
     *
     * @const string
     */
    const ISSUE_DELETE = 'tickit_issue.event.delete';

    /**
     * Constant representing the attachment upload event
     *
     * @const string
     */
    const ISSUE_ATTACHMENT_UPLOAD = 'tickit_issue.event.attachment_upload';
}
