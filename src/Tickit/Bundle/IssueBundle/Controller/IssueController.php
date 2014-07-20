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

use Symfony\Component\HttpFoundation\JsonResponse;
use Tickit\Component\Controller\Helper\BaseHelper;
use Tickit\Component\Controller\Helper\CsrfHelper;
use Tickit\Component\Controller\Helper\FormHelper;
use Tickit\Component\Entity\Manager\Issue\IssueManager;
use Tickit\Component\Model\Issue\Issue;

/**
 * Issue controller.
 *
 * Responsible for handling requests related to issues.
 *
 * @package Tickit\Bundle\IssueBundle\Controller
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class IssueController
{
    /**
     * String CSRF intention for deleting an issue
     *
     * @const string
     */
    const CSRF_DELETE_INTENTION = 'delete_issue';

    /**
     * The form controller helper
     *
     * @var FormHelper
     */
    private $formHelper;

    /**
     * The base controller helper
     *
     * @var BaseHelper
     */
    private $baseHelper;

    /**
     * The issue manager
     *
     * @var IssueManager
     */
    private $issueManager;

    /**
     * The CSRF controller helper
     *
     * @var CsrfHelper
     */
    private $csrfHelper;

    /**
     * Constructor.
     *
     * @param FormHelper   $formHelper
     * @param BaseHelper   $baseHelper
     * @param IssueManager $issueManager
     * @param CsrfHelper   $csrfHelper
     */
    public function __construct(
        FormHelper $formHelper,
        BaseHelper $baseHelper,
        IssueManager $issueManager,
        CsrfHelper $csrfHelper
    ) {
        $this->formHelper = $formHelper;
        $this->baseHelper = $baseHelper;
        $this->issueManager = $issueManager;
        $this->csrfHelper = $csrfHelper;
    }

    /**
     * Create action.
     *
     * Handles form submission request to create a new issue.
     *
     * @return JsonResponse
     */
    public function createAction()
    {
        $responseData = ['success' => false];
        $form = $this->formHelper->createForm('tickit_issue', new Issue());

        $form->handleRequest($this->baseHelper->getRequest());
        if ($form->isValid()) {
            $this->issueManager->create($form->getData());
            $responseData['success'] = true;
        } else {
            $formResponse = $this->formHelper->renderForm('TickitIssueBundle:Issue:create.html.twig', $form);
            $responseData['form'] = $formResponse->getContent();
        }

        return new JsonResponse($responseData);
    }

    /**
     * Edit action.
     *
     * Handles form submission request to update an existing issue.
     *
     * @param Issue $issue The issue that is being edited
     *
     * @return JsonResponse
     */
    public function editAction(Issue $issue)
    {
        $responseData = ['success' => false];
        $form = $this->formHelper->createForm('tickit_issue', $issue);

        $form->handleRequest($this->baseHelper->getRequest());
        if ($form->isValid()) {
            $this->issueManager->update($form->getData());
            $responseData['success'] = true;
            $responseData['returnUrl'] = $this->baseHelper->generateUrl('issue_index');
        } else {
            $formResponse = $this->formHelper->renderForm('TickitIssueBundle:Issue:edit.html.twig', $form);
            $responseData['form'] = $formResponse->getContent();
        }

        return new JsonResponse($responseData);
    }
}
