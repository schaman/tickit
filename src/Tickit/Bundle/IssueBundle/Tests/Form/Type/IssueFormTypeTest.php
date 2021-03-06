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
use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Tickit\Bundle\CoreBundle\Tests\Form\Type\AbstractFormTypeTestCase;
use Tickit\Bundle\IssueBundle\Form\Type\IssueAttachmentFormType;
use Tickit\Bundle\IssueBundle\Form\Type\IssueFormType;
use Tickit\Bundle\ProjectBundle\Form\Type\Picker\ProjectPickerType;
use Tickit\Bundle\UserBundle\Form\Type\Picker\UserPickerType;
use Tickit\Component\Model\Issue\Issue;
use Tickit\Component\Model\Issue\IssueAttachment;
use Tickit\Component\Model\Issue\IssueStatus;
use Tickit\Component\Model\Issue\IssueType;
use Tickit\Component\Model\Project\Project;
use Tickit\Component\Model\User\User;

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
    private static $project;

    /**
     * @var IssueType
     */
    private static $issueType;

    /**
     * @var IssueStatus
     */
    private static $issueStatus;

    /**
     * @var Collection
     */
    private static $attachments;

    /**
     * @var User
     */
    private static $assignedUser;

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

        $this->assertEquals(1, $form->get('project')->getConfig()->getOption('max_selections'));
        $this->assertEquals(1, $form->get('assignedTo')->getConfig()->getOption('max_selections'));
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

        $this->loadFixtures();
        $attachment = static::$attachments->first();

        $rawData = [
            'title' => 'Search field does not open on iPhone',
            'attachments' => [
                0 => [
                    'id' => '',
                    'file' => $attachment->getFile(),
                    'mimeType' => ''
                ]
            ],
            'project' => static::$project->getId(),
            'priority' => Issue::PRIORITY_HIGH,
            'type' => static::$issueType->getId(),
            'status' => static::$issueStatus->getId(),
            'description' => implode(' ', $faker->paragraphs(2)),
            'estimatedHours' => '3',
            'actualHours' => '2',
            'assignedTo' => static::$assignedUser->getId()
        ];

        $expected = new Issue();
        $attachments = static::$attachments;
        foreach ($attachments as $attachment) {
            $attachment->setIssue($expected);
        }
        $expected->setTitle($rawData['title'])
                 ->setDescription($rawData['description'])
                 ->setEstimatedHours($rawData['estimatedHours'])
                 ->setActualHours($rawData['actualHours'])
                 ->setPriority($rawData['priority'])
                 ->setProject(static::$project)
                 ->setType(static::$issueType)
                 ->setStatus(static::$issueStatus)
                 ->setAttachments(static::$attachments)
                 ->setAssignedTo(static::$assignedUser);

        return [
            [$rawData, $expected]
        ];
    }

    /**
     * Gets form extensions for the test
     *
     * @return array
     */
    protected function getExtensions()
    {
        $this->loadFixtures();
        $entityData = [
            'Tickit\\Component\\Model\\Issue\\IssueType' => [static::$issueType],
            'Tickit\\Component\\Model\\Issue\\IssueStatus' => [static::$issueStatus]
        ];
        $this->enableEntityTypeExtension($entityData);

        $extensions = parent::getExtensions();
        $self = $this;

        $transformer = $this->getMockPickerDataTransformer();
        $transformer->expects($this->any())
                    ->method('transform')
                    ->will(
                        $this->returnCallback(
                            function ($value) use ($self) {
                                if ($value instanceof Project) {
                                    return static::$project->getId();
                                }

                                if ($value instanceof User) {
                                    return static::$assignedUser->getId();
                                }

                                return null;
                            }
                        )
                    );
        $transformer->expects($this->any())
                    ->method('reverseTransform')
                    ->will(
                        $this->returnCallback(
                            function ($value) use ($self) {
                                if ($value == static::$project->getId()) {
                                    return static::$project;
                                }

                                if ($value == static::$assignedUser->getId()) {
                                    return static::$assignedUser;
                                }

                                return null;
                            }
                        )
                    );

        $userPicker = new UserPickerType($transformer);
        $projectPicker = new ProjectPickerType($transformer);

        $extensions[] = new PreloadedExtension(
            [$userPicker->getName() => $userPicker],
            []
        );

        $extensions[] = new PreloadedExtension(
            [$projectPicker->getName() => $projectPicker],
            []
        );

        $issueAttachmentType = new IssueAttachmentFormType();
        $extensions[] = new PreloadedExtension(
            [$issueAttachmentType->getName() => $issueAttachmentType],
            []
        );

        return $extensions;
    }

    /**
     * Loads fixtures onto the test
     */
    private function loadFixtures()
    {
        if (static::$project !== null) {
            return;
        }

        $project = new Project();
        $project->setId(19);

        $issueType = new IssueType();
        $issueType->setId(2);

        $issueStatus = new IssueStatus();
        $issueStatus->setId(6);

        $originalName = uniqid() . '.xml';
        $path = sys_get_temp_dir() . '/' . $originalName;
        file_put_contents($path, 'test');
        $attachment1 = new IssueAttachment();
        $attachment1->setMimeType('text/plain')
                    ->setFile(new UploadedFile($path, $originalName));

        $assignedUser = new User();
        $assignedUser->setId(7);

        static::$project = $project;
        static::$issueType = $issueType;
        static::$issueStatus = $issueStatus;
        static::$attachments = new ArrayCollection([$attachment1]);
        static::$assignedUser = $assignedUser;
    }
}
