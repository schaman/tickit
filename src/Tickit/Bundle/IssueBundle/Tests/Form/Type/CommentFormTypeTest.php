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
use Tickit\Bundle\IssueBundle\Form\EventListener\CommentCreatedByFormSubscriber;
use Tickit\Bundle\IssueBundle\Form\Type\CommentFormType;
use Tickit\Component\Model\Issue\Comment;
use Tickit\Component\Model\Issue\Issue;

/**
 * CommentFormTypeTest
 *
 * @package Tickit\Bundle\IssueBundle\Tests\Form\Type
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class CommentFormTypeTest extends AbstractFormTypeTestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $issueDataTransformer;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $commentCreatedBySubscriber;

    /**
     * Setup
     */
    protected function setUp()
    {
        parent::setUp();

        $this->issueDataTransformer = $this->getMockDataTransformer();
        $this->commentCreatedBySubscriber = $this->getMockBuilder('\Tickit\Bundle\IssueBundle\Form\EventListener\CommentCreatedByFormSubscriber')
                                                 ->setMethods(['setCreatedBy'])
                                                 ->disableOriginalConstructor()
                                                 ->getMock();

        $this->formType = new CommentFormType($this->issueDataTransformer, $this->commentCreatedBySubscriber);
    }

    /**
     * @dataProvider getValidDataFixtures
     */
    public function testSubmitValidDataBlah(array $data, Comment $expected)
    {
        $this->issueDataTransformer->expects($this->once())
                                   ->method('reverseTransform')
                                   ->with($data['issue'])
                                   ->will($this->returnValue($expected->getIssue()));

        $form = $this->factory->create($this->formType);
        $form->submit($data);

        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($expected, $form->getData());

        $expectedViewComponents = array_keys($data);
        $this->assertViewHasComponents($expectedViewComponents, $form->createView());
    }

    /**
     * @return array
     */
    public function getValidDataFixtures()
    {
        $faker = $this->getFakerGenerator();

        $rawData = [
            'message' => $faker->paragraph(),
            'issue' => $faker->numberBetween(1, 100000)
        ];

        $comment = new Comment();
        $issue = new Issue();
        $issue->setId($rawData['issue']);

        $comment->setMessage($rawData['message'])
                ->setIssue($issue);

        return [
            [$rawData, $comment]
        ];
    }
}
