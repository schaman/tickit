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

namespace Tickit\Component\Issue\Listener;

use Symfony\Component\Security\Core\SecurityContextInterface;
use Tickit\Component\Entity\Event\EntityEvent;

/**
 * Listener for setting "createdBy" property on an Issue
 *
 * @package Tickit\Component\Issue\Listener
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class IssueCreatedByListener
{
    /**
     * A security context
     *
     * @var SecurityContextInterface
     */
    private $securityContext;

    /**
     * Constructor.
     *
     * @param SecurityContextInterface $securityContext A security context
     */
    public function __construct(SecurityContextInterface $securityContext)
    {
        $this->securityContext = $securityContext;
    }

    /**
     * Hooks into the issue creation.
     *
     * Sets the "createdBy" property on the Issue to the currently
     * authenticated user.
     *
     * @param EntityEvent $event The event object containing the issue
     */
    public function onIssueCreate(EntityEvent $event)
    {
        $user = $this->securityContext->getToken()->getUser();

        $event->getEntity()->setCreatedBy($user);
    }
}
