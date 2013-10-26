<?php

/*
 * Tickit, an open source web based bug management tool.
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
class UserPickerTypeTest extends AbstractFormTypeTestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $converter;

    /**
     * Setup
     */
    protected function setUp()
    {
        parent::setUp();

        $this->converter = $this->getMock('\Tickit\CoreBundle\Form\Type\Picker\EntityConverterInterface');

        $this->formType = new UserPickerType($this->converter);
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

        $this->converter->expects($this->once())
            ->method('convert')
            ->with(123)
            ->will($this->returnValue('Mark Wilson'));

        $formData = array(
            'user_ids' => array(
                123
            )
        );

        $form->setData($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($formData, $form->getData());

        $formView = $form->createView();

        $this->assertArrayHasKey('displayValues', $formView);
        $this->assertEquals($user->getFullName(), $formView->displayValues);
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

        $this->converter->expects($this->exactly(2))
             ->method('convert')
             ->will($this->onConsecutiveCalls($user1->getFullName(), $user2->getFullName()));

        $this->converter->expects($this->at(0))
                        ->method('convert')
                        ->with(123);

        $this->converter->expects($this->at(1))
                        ->method('convert')
                        ->with(456);

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

        $this->assertArrayHasKey('displayValues', $formView);
        $this->assertEquals($user1->getFullName() . ',' . $user2->getFullName(), $formView->displayValues);
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

        $this->converter->expects($this->once())
             ->method('convert')
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

        $this->assertArrayHasKey('displayValues', $formView);
        $this->assertEquals('', $formView->displayValues);
    }

    /**
     * Tests form data of an invalid user based on the restrictions returns the correct display names - none
     */
    public function testInvalidUserSubmissionWithFormRestriction()
    {
        $this->markTestIncomplete('Not yet implemented restrictions');
    }
}
