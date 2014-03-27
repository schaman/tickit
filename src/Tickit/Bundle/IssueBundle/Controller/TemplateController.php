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

namespace Tickit\Bundle\IssueBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Tickit\Component\Controller\Helper\FormHelper;
use Tickit\Component\Entity\Manager\IssueManager;
use Tickit\Component\Model\Issue\Issue;

/**
 * Template controller.
 *
 * Serves template content for the bundle
 *
 * @package Tickit\Bundle\IssueBundle\Controller
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class TemplateController
{
    /**
     * The controller form helper
     *
     * @var FormHelper
     */
    private $formHelper;

    /**
     * The issue manager
     *
     * @var IssueManager
     */
    private $issueManager;

    /**
     * Constructor.
     *
     * @param FormHelper   $formHelper   The controller form helper
     * @param IssueManager $issueManager The issue manager
     */
    public function __construct(FormHelper $formHelper, IssueManager $issueManager)
    {
        $this->formHelper = $formHelper;
        $this->issueManager = $issueManager;
    }

    /**
     * Renders a filter form
     *
     * @return Response
     */
    public function filterFormAction()
    {
        $form = $this->formHelper->createForm('tickit_issue_filters');

        return $this->formHelper->renderForm('TickitIssueBundle:Filter:filter-form.html.twig', $form);
    }

    /**
     * Serves form content for creating an issue
     *
     * @return Response
     */
    public function createIssueFormAction()
    {
        $form = $this->formHelper->createForm('tickit_issue', $this->issueManager->createIssue(true));

        return $this->formHelper->renderForm('TickitIssueBundle:Issue:create.html.twig', $form);
    }

    /**
     * Serves form content for editing an issue
     *
     * @param Issue $issue The issue being edited
     *
     * @return Response
     */
    public function editIssueFormAction(Issue $issue)
    {
        $form = $this->formHelper->createForm('tickit_issue', $issue);

        return $this->formHelper->renderForm('TickitIssueBundle:Issue:edit.html.twig', $form);
    }
}
