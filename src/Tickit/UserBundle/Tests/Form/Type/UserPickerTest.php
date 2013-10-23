<?php

/*
 * 
 * Tickit, an source web based bug management tool.
 * 
 * Copyright (C) 2013  Tickit Project <http://tickit.io>
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
 * 
 */

namespace Tickit\UserBundle\Tests\Form\Type;

use Tickit\CoreBundle\Tests\Form\Type\AbstractFormTypeTestCase;
use Tickit\UserBundle\Entity\User;
use Tickit\UserBundle\Form\Type\UserPickerType;

/**
 * User picker form field test
 *
 * @package Tickit\UserBundle\Tests\Form\Type
 * @author  Mark Wilson <mark@89allport.co.uk>
 */
class UserPickerTest extends AbstractFormTypeTestCase
{
    // TODO: remove this when moving to extend AbstractFormTypeTestCase
    protected $formType;

    /**
     * Mocked user manager
     *
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $userManager;

    /**
     * Set up the mock user manager and form type
     *
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();

        $mockUserManager = $this->getMockBuilder('Tickit\\UserBundle\\Manager\\UserManager')
                                ->disableOriginalConstructor()
                                ->getMock();

        $this->userManager = $mockUserManager;

        $this->formType = new UserPickerType($this->userManager);
    }

    /**
     * Tests form data of single user Id resolves to correct display name
     */
    public function testSingleUserSubmission()
    {
        $form = $this->factory->create($this->formType);

        $user = new User();
        $user->setForename('Mark')
            ->setSurname('Wilson')
            ->setEmail('mark@89allport.co.uk')
            ->setPlainPassword('password');

        $this->userManager->expects($this->once())
            ->method('find')
            ->with(123)
            ->will($this->returnValue($user));

        $formData = array(
            'user_ids' => array(
                123
            )
        );

        $form->setData($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($formData, $form->getData());

        $formView = $form->createView();

        $this->assertArrayHasKey('display_names', $formView);
        $this->assertEquals($user->getFullName(), $formView->display_names);
    }

    /**
     * Tests form data of 2 user Ids resolves to correct display names
     */
    public function testDoubleUserSubmission()
    {
        $form = $this->factory->create($this->formType);

        $user1 = new User();
        $user1->setForename('Mark')
            ->setSurname('Wilson')
            ->setEmail('mark@89allport.co.uk')
            ->setPlainPassword('password');

        $user2 = new User();
        $user2->setForename('Joe')
            ->setSurname('Bloggs')
            ->setEmail('joe.bloggs@example.com')
            ->setPlainPassword('password');

        $this->userManager->expects($this->exactly(2))
            ->method('find')
            ->will($this->onConsecutiveCalls($user1, $user2));

        $formData = array(
            'user_ids' => array(
                123,
                456
            )
        );

        $form->setData($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($formData, $form->getData());

        $formView = $form->createView();

        $this->assertArrayHasKey('display_names', $formView);
        $this->assertEquals($user1->getFullName() . ',' . $user2->getFullName(), $formView->display_names);
    }

    /**
     * Tests invalid user data returns correct display names - none
     */
    public function testInvalidUserData()
    {
        $form = $this->factory->create($this->formType);

        $user = new User();
        $user->setForename('Mark')
            ->setSurname('Wilson')
            ->setEmail('mark@89allport.co.uk')
            ->setPlainPassword('password');

        $this->userManager->expects($this->once())
            ->method('find')
            ->with(123)
            ->will($this->returnValue(null));

        $formData = array(
            'user_ids' => array(
                123
            )
        );

        $form->setData($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($formData, $form->getData());

        $formView = $form->createView();

        $this->assertArrayHasKey('display_names', $formView);
        $this->assertEquals('', $formView->display_names);
    }

    /**
     * Tests form data of an invalid user based on the restrictions returns the correct display names - none
     */
    public function testInvalidUserSubmissionWithFormRestriction()
    {
        $this->markTestIncomplete('Not yet implemented restrictions');
    }
}
