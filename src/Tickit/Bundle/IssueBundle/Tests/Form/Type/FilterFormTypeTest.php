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

namespace Tickit\Bundle\IssueBundle\Tests\Form\Type;

use Tickit\Bundle\CoreBundle\Tests\Form\Type\AbstractFormTypeTestCase;
use Tickit\Bundle\IssueBundle\Form\Type\FilterFormType;
use Tickit\Component\Model\Issue\Issue;
use Tickit\Component\Model\Issue\IssueStatus;
use Tickit\Component\Model\Issue\IssueType;

/**
 * FilterFormType tests
 *
 * @package Tickit\Bundle\IssueBundle\Tests\Form\Type
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class FilterFormTypeTest extends AbstractFormTypeTestCase
{
    private $issueTypes = [];
    private $issueStatuses = [];

    /**
     * Setup
     */
    protected function setUp()
    {
        $type1 = new IssueType();
        $type1->setId(1)
              ->setName('Bug');
        $type2 = new IssueType();
        $type2->setId(2)
              ->setName('User Story');

        $status1 = new IssueStatus();
        $status1->setName('Open')
                ->setId(2);
        $status2 = new IssueStatus();
        $status2->setName('Closed')
                ->setId(3);

        $this->issueTypes = [$type1, $type2];
        $this->issueStatuses = [$status1, $status2];

        parent::setUp();

        $this->formType = new FilterFormType();
    }

    /**
     * Tests the form submit
     */
    public function testSubmitValidIssueFilterData()
    {
        $form = $this->factory->create($this->formType);
        $data = [
            'number' => 'PRO102390',
            'title' => 'title',
            'type' => 1,
            'status' => 3,
            'priority' => Issue::PRIORITY_LOW
        ];

        $expectedData = [
            'number' => 'PRO102390',
            'title' => 'title',
            'type' => $this->issueTypes[0],
            'status' => $this->issueStatuses[1],
            'priority' => Issue::PRIORITY_LOW
        ];

        $form->submit($data);
        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($expectedData, $form->getData());

        $expectedFields = array_keys($data);
        $this->assertViewHasComponents($expectedFields, $form->createView());
    }

    /**
     * Configures form type extensions for the tests
     */
    protected function configureExtensions()
    {
        $data = [
            'Tickit\\Component\\Model\\Issue\\IssueType' => $this->issueTypes,
            'Tickit\\Component\\Model\\Issue\\IssueStatus' => $this->issueStatuses
        ];

        $this->enableEntityTypeExtension($data);
    }
}
