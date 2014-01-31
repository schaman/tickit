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

namespace Tickit\Bundle\UserBundle\Tests\Form\Type;

use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Role\Role;
use Tickit\Bundle\CoreBundle\Tests\Form\Type\AbstractFormTypeTestCase;
use Tickit\Bundle\UserBundle\Form\Type\RolesFormType;
use Tickit\Component\Model\User\User;

/**
 * RolesFormType tests
 *
 * @package Tickit\Bundle\UserBundle\Tests\Form\Type
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class RolesFormTypeTest extends AbstractFormTypeTestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $roleProvider;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $roleDecorator;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $securityContext;

    /**
     * Setup
     */
    protected function setUp()
    {
        parent::setUp();

        $this->securityContext = $this->getMock('\Symfony\Component\Security\Core\SecurityContextInterface');
        $this->roleProvider = $this->getMock('\Tickit\Component\Security\Role\Provider\RoleProviderInterface');
        $this->roleDecorator = $this->getMock('\Tickit\Component\Security\Role\Decorator\RoleDecoratorInterface');
    }

    /**
     * Tests the field options
     */
    public function testFieldBuildsWithCorrectRoles()
    {
        $roles = [
            new Role('ROLE_USER'), new Role('ROLE_ADMIN'), new Role('ROLE_SUPER_ADMIN')
        ];

        $user = new User();
        $user->setRoles(['ROLE_ADMIN']);

        $this->trainSecurityContextToReturnIsGranted();
        $this->securityContext->expects($this->once())
                              ->method('getToken')
                              ->will($this->returnValue(new UsernamePasswordToken($user, 'password', 'main')));

        $reachableRoles = [new Role('ROLE_USER'), new Role('ROLE_ADMIN')];
        $this->roleProvider->expects($this->once())
                           ->method('getReachableRolesForRole')
                           ->with($user->getRoles())
                           ->will($this->returnValue($reachableRoles));

        $this->roleProvider->expects($this->once())
                           ->method('getAllRoles')
                           ->will($this->returnValue($roles));

        $this->roleDecorator->expects($this->exactly(3))
                            ->method('decorate')
                            ->will($this->onConsecutiveCalls('default', 'admin', 'super admin'));

        foreach ($roles  as $index => $role) {
            $this->roleDecorator->expects($this->at($index))
                                ->method('decorate')
                                ->with($role);
        }

        $form = $this->factory->create($this->getFormType());

        $choices = $form->getConfig()->getOption('choices');
        $this->assertCount(2, $choices);

        $expectedChoices = [
            'ROLE_USER' => 'default',
            'ROLE_ADMIN' => 'admin'
        ];
        $this->assertEquals($expectedChoices, $choices);

        $readOnlyChoices = $form->getConfig()->getOption('read_only_choices');
        $this->assertCount(1, $readOnlyChoices);
        $this->assertEquals(['ROLE_SUPER_ADMIN' => 'super admin'], $readOnlyChoices);

        $view = $form->createView();
        $this->assertEquals($readOnlyChoices, $view->vars['read_only_choices']);
    }

    /**
     * Tests the field options
     */
    public function testFieldBuildsWithNoRoles()
    {
        $user = new User();
        $user->setRoles(['ROLE_ADMIN']);

        $this->trainSecurityContextToReturnIsGranted();
        $this->securityContext->expects($this->once())
                              ->method('getToken')
                              ->will($this->returnValue(new UsernamePasswordToken($user, 'password', 'main')));

        $this->roleProvider->expects($this->once())
                           ->method('getReachableRolesForRole')
                           ->with($user->getRoles())
                           ->will($this->returnValue([]));

        $this->roleProvider->expects($this->once())
                           ->method('getAllRoles')
                           ->will($this->returnValue([]));

        $this->roleDecorator->expects($this->never())
                            ->method('decorate');

        $form = $this->factory->create($this->getFormType());
        $choices = $form->getConfig()->getOption('choices');

        $this->assertEmpty($choices);

        $view = $form->createView();
        $this->assertEmpty($view->vars['read_only_choices']);
    }

    /**
     * Tests the field options
     *
     * @expectedException \RuntimeException
     */
    public function testFieldThrowsExceptionWhenUserIsNotAuthenticated()
    {
        $this->trainSecurityContextToReturnIsGranted(false);

        $this->factory->create($this->getFormType());
    }

    /**
     * Gets a new instance of the form type
     *
     * @return RolesFormType
     */
    private function getFormType()
    {
        return new RolesFormType($this->roleProvider, $this->roleDecorator, $this->securityContext);
    }

    private function trainSecurityContextToReturnIsGranted($value = true)
    {
        $this->securityContext->expects($this->once())
                              ->method('isGranted')
                              ->with('IS_AUTHENTICATED_REMEMBERED')
                              ->will($this->returnValue($value));
    }
}
