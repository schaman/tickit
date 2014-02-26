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

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Faker\Factory;
use Tickit\Bundle\CoreBundle\Tests\Form\Type\AbstractFormTypeTestCase;
use Tickit\Bundle\IssueBundle\Form\Type\IssueFormType;
use Tickit\Component\Model\Issue\Issue;
use Tickit\Component\Model\Issue\IssueAttachment;
use Tickit\Component\Model\Issue\IssueStatus;
use Tickit\Component\Model\Issue\IssueType;
use Tickit\Component\Model\Project\Project;

/**
 * IssueFormType tests
 *
 * @package Tickit\Bundle\IssueBundle\Tests\Form\Type
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class IssueFormTypeTest extends AbstractFormTypeTestCase
{
    /**
     * @var Project
     */
    private $project;

    /**
     * @var IssueType
     */
    private $issueType;

    /**
     * @var IssueStatus
     */
    private $issueStatus;

    /**
     * @var Collection
     */
    private $attachments;

    /**
     * Setup
     */
    protected function setUp()
    {
        parent::setUp();

        $this->formType = new IssueFormType();
    }

    /**
     * Tests the submit() method
     *
     * @dataProvider getValidDataFixtures
     */
    public function testSubmitValidData($data, $expected)
    {
        $form = $this->factory->create($this->formType);
        $form->submit($data);

        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($expected, $form->getData());

        $expectedViewComponents = array_keys($data);
        $this->assertViewHasComponents($expectedViewComponents, $form->createView());
    }

    /**
     * We initialise the fixture state of the test in here.
     *
     * This is called before setUp()... go figure
     *
     * @return array
     */
    public function getValidDataFixtures()
    {
        $faker = Factory::create();

        $project = new Project();
        $project->setId(19);

        $issueType = new IssueType();
        $issueType->setId(2);

        $issueStatus = new IssueStatus();
        $issueStatus->setId(6);

        $attachment1 = new IssueAttachment();
        $attachment1->setId(1);
        $attachment2 = new IssueAttachment();
        $attachment2->setId(2);

        $this->project = $project;
        $this->issueType = $issueType;
        $this->issueStatus = $issueStatus;
        $this->attachments = new ArrayCollection([$attachment1, $attachment2]);

        $rawData = [
            'number' => 'PROJ12345',
            'title' => 'Search field does not open on iPhone',
            'attachments' => [1, 2],
            'project' => $this->project->getId(),
            'priority' => Issue::PRIORITY_HIGH,
            'type' => $this->issueType->getId(),
            'status' => $this->issueStatus->getId(),
            'description' => implode(' ', $faker->paragraphs(2)),
            'estimatedHours' => 3,
            'actualHours' => 2,
            'assignedTo' => 7
        ];

        $expected = new Issue();
        $expected->setNumber($rawData['number'])
                 ->setTitle($rawData['title'])
                 ->setDescription($rawData['description'])
                 ->setEstimatedHours($rawData['estimatedHours'])
                 ->setActualHours($rawData['actualHours'])
                 ->setPriority($rawData['priority'])
                 ->setProject($this->project)
                 ->setType($this->issueType)
                 ->setStatus($this->issueStatus)
                 ->setAttachments($this->attachments);

        return [
            [$rawData, $expected]
        ];
    }
}
