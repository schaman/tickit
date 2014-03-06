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

use Tickit\Component\Entity\Event\EntityEvent;
use Tickit\Component\Entity\Repository\IssueRepositoryInterface;
use Tickit\Component\Model\Issue\Issue;

/**
 * Listener for issue number creation.
 *
 * Used to hook into issue creation so that an issue is assigned
 * the correct number.
 *
 * @package Tickit\Component\Issue\Listener
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class IssueNumberListener
{
    /**
     * An issue repository
     *
     * @var IssueRepositoryInterface
     */
    private $issueRepository;

    /**
     * Constructor
     *
     * @param IssueRepositoryInterface $issueRepository
     */
    public function __construct(IssueRepositoryInterface $issueRepository)
    {
        $this->issueRepository = $issueRepository;
    }

    /**
     * Hooks into the issue create event.
     *
     * Sets the issue number on the issue based on the last used
     * number on the issue's project.
     *
     * @param EntityEvent $event The entity event object
     */
    public function onIssueCreate(EntityEvent $event)
    {
        /** @var Issue $issue */
        $issue = $event->getEntity();
        $project = $issue->getProject();

        $lastIssueNumber = (integer) $this->issueRepository->findLastIssueNumberForProject($project);

        $issue->setNumber(++$lastIssueNumber);
    }
}
