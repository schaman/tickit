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

namespace Tickit\Bundle\UserBundle\Tests\Form\Type;

use Symfony\Component\Form\Extension\Validator\ValidatorExtension;
use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\Security\Core\Role\Role;
use Symfony\Component\Validator\Validation;
use Tickit\Bundle\CoreBundle\Tests\Form\Type\AbstractFormTypeTestCase;
use Tickit\Bundle\UserBundle\Form\Type\RolesFormType;
use Tickit\Component\Model\User\User;
use Tickit\Bundle\UserBundle\Form\Type\UserFormType;

/**
 * UserFormType tests.
 *
 * @package Tickit\Bundle\UserBundle\Tests\Form\Type
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class UserFormTypeTest extends AbstractFormTypeTestCase
{
    /**
     * Set up
     */
    protected function setUp()
    {
        parent::setUp();

        $this->formType = new UserFormType();
    }

    /**
     * Tests the form submit
     *
     * @dataProvider getUser
     */
    public function testSubmitValidData(User $user)
    {
        $form = $this->factory->create($this->formType);

        $form->setData($user);

        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($user, $form->getData());

        $expectedViewComponents = array('id', 'forename', 'surname', 'username', 'email', 'password', 'roles');
        $this->assertViewHasComponents($expectedViewComponents, $form->createView());

        $passwordForm = $form->get('password');
        $firstPasswordFieldOptions = $passwordForm->getConfig()->getOption('first_options');
        $secondPasswordFieldOptions = $passwordForm->getConfig()->getOption('second_options');

        $this->assertEquals('Password', $firstPasswordFieldOptions['label']);
        $this->assertEquals('Confirm Password', $secondPasswordFieldOptions['label']);
    }

    /**
     * Tests the form handles existing User instance
     *
     * @dataProvider getUser
     */
    public function testFormHandlesExistingUserCorrectly(User $user)
    {
        $form = $this->factory->create($this->formType, $user);

        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($user, $form->getData());

        $expectedViewComponents = array('id', 'forename', 'surname', 'username', 'email', 'password', 'roles');
        $this->assertViewHasComponents($expectedViewComponents, $form->createView());

        $passwordForm = $form->get('password');
        $firstPasswordFieldOptions = $passwordForm->getConfig()->getOption('first_options');
        $secondPasswordFieldOptions = $passwordForm->getConfig()->getOption('second_options');

        $this->assertEquals('New Password', $firstPasswordFieldOptions['label']);
        $this->assertEquals('Confirm New Password', $secondPasswordFieldOptions['label']);
    }

    /**
     * Gets user for tests
     *
     * @return array
     */
    public function getUser()
    {
        $user = new User();
        $user->setId(1)
             ->setAdmin(false)
             ->setForename('Forename')
             ->setSurname('Surname')
             ->setEmail('email@domain.com')
             ->setSurname('username')
             ->setPlainPassword('plain password')
             ->setRoles(['ROLE_ADMIN']);

        return [[$user]];
    }

    /**
     * Gets form factory extensions
     */
    protected function configureExtensions()
    {
        $this->enableValidatorExtension();

        $mockRoleProvider = $this->getMock('\Tickit\Component\Security\Role\Provider\RoleProviderInterface');
        $mockRoleDecorator = $this->getMock('\Tickit\Component\Security\Role\Decorator\RoleDecoratorInterface');

        $mockRoleProvider->expects($this->any())
                         ->method('getRoles')
                         ->will($this->returnValue([new Role('ROLE_USER'), new Role('ROLE_ADMIN')]));

        $mockRoleDecorator->expects($this->any())
                          ->method('decorate')
                          ->will($this->returnValue('decorated role'));

        $rolesFormType = new RolesFormType($mockRoleProvider, $mockRoleDecorator);
        $this->extensions[] = new PreloadedExtension([$rolesFormType->getName() => $rolesFormType], []);
    }
}
