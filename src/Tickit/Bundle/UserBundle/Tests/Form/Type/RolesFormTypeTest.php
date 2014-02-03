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
use Symfony\Component\Security\Core\Role\RoleInterface;
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
     *
     * @dataProvider getFormDataFixtures
     */
    public function testFormBuildsWithCorrectRoles($formData, $allRoles, $reachableRoles, $user)
    {
        $roleMap = $this->getRoleMap();

        $this->trainSecurityContextToReturnIsGranted();
        $this->trainSecurityContextToReturnUsernamePasswordToken($user);

        $this->trainRoleProviderToReturnReachableRoles($user, $reachableRoles);
        $this->trainRoleProviderToReturnAllRoles($allRoles);

        $this->trainRoleDecoratorToReturnDecoratedRoles($allRoles, $roleMap);

        $form = $this->factory->create($this->getFormType(), $formData);
        $expectedChoices = $this->getExpectedRoleChoices($reachableRoles);

        $choices = $form->getConfig()->getOption('choices');
        $this->assertEquals($expectedChoices, $choices);

        $expectedReadOnlyChoices = $this->getExpectedReadOnlyChoices($allRoles, $reachableRoles);
        $readOnlyChoices = $form->getConfig()->getOption('read_only_choices');

        $this->assertEquals($expectedReadOnlyChoices, $readOnlyChoices);

        $view = $form->createView();
        $this->assertEquals($readOnlyChoices, $view->vars['read_only_choices']);
        $this->assertEquals($formData, $form->getData());
        if (null === $formData) {
            $this->assertEquals([], $view->vars['granted_roles']);
        } else {
            $this->assertEquals($formData, $view->vars['granted_roles']);
        }
    }

    /**
     * Tests the form submit
     *
     * @dataProvider getSubmittedDataFixtures
     */
    public function testFormSubmit($submittedData, $allRoles, $reachableRoles, $user, $formData, $expectedData)
    {
        $roleMap = $this->getRoleMap();
        $this->trainSecurityContextToReturnIsGranted();
        $this->trainSecurityContextToReturnUsernamePasswordToken($user);

        $this->trainRoleProviderToReturnReachableRoles($user, $reachableRoles);
        $this->trainRoleProviderToReturnAllRoles($allRoles);

        $this->trainRoleDecoratorToReturnDecoratedRoles($allRoles, $roleMap);

        $form = $this->factory->create($this->getFormType(), $formData);
        $this->assertEquals($formData, $form->getData());

        $form->submit($submittedData);
        $this->assertEquals($expectedData, $form->getData());

        $expectedChoices = $this->getExpectedRoleChoices($reachableRoles);
        $choices = $form->getConfig()->getOption('choices');
        $this->assertEquals($expectedChoices, $choices);

        $readOnlyChoices = $form->getConfig()->getOption('read_only_choices');
        $expectedReadOnlyChoices = $this->getExpectedReadOnlyChoices($allRoles, $reachableRoles);
        $this->assertEquals($expectedReadOnlyChoices, $readOnlyChoices);

        $view = $form->createView();
        $this->assertEquals($readOnlyChoices, $view->vars['read_only_choices']);
        $this->assertEquals($expectedData, $view->vars['granted_roles']);
    }

    /**
     * Tests the field options
     *
     * @dataProvider getFormDataFixtures
     */
    public function testFieldBuildsWithNoRoles($formData)
    {
        $user = new User();
        $user->setRoles(['ROLE_ADMIN']);

        $this->trainSecurityContextToReturnIsGranted();
        $this->trainSecurityContextToReturnUsernamePasswordToken($user);
        $this->trainRoleProviderToReturnReachableRoles($user, []);
        $this->trainRoleProviderToReturnAllRoles([]);

        $this->roleDecorator->expects($this->never())
                            ->method('decorate');

        $form = $this->factory->create($this->getFormType(), $formData);
        $choices = $form->getConfig()->getOption('choices');

        $this->assertEmpty($choices);

        $view = $form->createView();
        $this->assertEmpty($view->vars['read_only_choices']);
        $this->assertEquals($formData, $form->getData());
        if (null === $formData) {
            $this->assertEquals([], $view->vars['granted_roles']);
        } else {
            $this->assertEquals($formData, $view->vars['granted_roles']);
        }
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
     * Data provider for form data
     *
     * @return array
     */
    public function getFormDataFixtures()
    {
        $roleUserStr = 'ROLE_USER';
        $roleAdminStr = 'ROLE_ADMIN';
        $roleSuperAdminStr = 'ROLE_SUPER_ADMIN';
        $roleUser = new Role($roleUserStr);
        $roleAdmin = new Role($roleAdminStr);
        $roleSuperAdmin = new Role($roleSuperAdminStr);

        $fullRoleStrSet = [$roleUserStr, $roleAdminStr, $roleSuperAdminStr];
        $fullRoleSet = [$roleUser, $roleAdmin, $roleSuperAdmin];

        $reachableRoles = [$roleUser, $roleAdmin];

        // user currently using the form (not the user being edited)
        $adminUser = new User();
        $adminUser->setRoles([$roleAdminStr]);

        return [
            [
                // roles that the form will be populated with (roles that the user we are editing
                // currently has granted)
                $fullRoleStrSet,
                // the full role set, returned by RoleProviderInterface::getAllRoles()
                $fullRoleSet,
                // roles reachable from the currently logged in user's granted roles
                $reachableRoles,
                // the user that is doing the editing, i.e. the user who is logged in
                $adminUser
            ],
            [[$roleUserStr], $fullRoleSet, $reachableRoles, $adminUser],
            [[$roleAdminStr, $roleSuperAdminStr], $fullRoleSet, $reachableRoles, $adminUser],
            [null, $fullRoleSet, $reachableRoles, $adminUser],
            [$fullRoleStrSet, $fullRoleSet, [$roleUser], new User()]
        ];
    }

    /**
     * Data provider for form submit tests
     *
     * @return array
     */
    public function getSubmittedDataFixtures()
    {
        $roleUserStr = 'ROLE_USER';
        $roleAdminStr = 'ROLE_ADMIN';
        $roleSuperAdminStr = 'ROLE_SUPER_ADMIN';
        $roleUser = new Role($roleUserStr);
        $roleAdmin = new Role($roleAdminStr);
        $roleSuperAdmin = new Role($roleSuperAdminStr);

        // user currently using the form (not the user being edited)
        $user = new User();
        $user->setRoles([$roleUserStr]);

        return [
            // here we are essentially simulating what happens when a user with a more
            // restricted role set edits a user with more roles (for example, an admin
            // editing a super admin)
            [
                // the data that was submitted from the form in the request
                [$roleUserStr],
                // the full role set, returned by RoleProviderInterface::getAllRoles()
                [$roleUser, $roleAdmin, $roleSuperAdmin],
                // roles reachable from the currently logged in user's granted roles,
                // this will restrict the available choices on the form so that a user
                // cannot grant roles that they themselves do not have access to
                [$roleUser],
                // the user that is doing the editing, i.e. the user who is logged in
                $user,
                // formData, this is the data that the form was initially populated with
                [$roleUserStr, $roleAdminStr, $roleSuperAdminStr],
                // the expected data after $form->submit()
                [$roleUserStr, $roleAdminStr, $roleSuperAdminStr]
            ]
        ];
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

    private function trainSecurityContextToReturnUsernamePasswordToken($user)
    {
        $this->securityContext->expects($this->once())
                              ->method('getToken')
                              ->will($this->returnValue(new UsernamePasswordToken($user, 'password', 'main')));
    }

    private function trainRoleProviderToReturnReachableRoles(User $user, array $returnRoles)
    {
        $this->roleProvider->expects($this->once())
                           ->method('getReachableRolesForRole')
                           ->with($user->getRoles())
                           ->will($this->returnValue($returnRoles));
    }

    private function trainRoleProviderToReturnAllRoles(array $roles)
    {
        $this->roleProvider->expects($this->once())
                           ->method('getAllRoles')
                           ->will($this->returnValue($roles));
    }

    private function getRoleMap()
    {
        return [
            'ROLE_USER' => 'default',
            'ROLE_ADMIN' => 'admin',
            'ROLE_SUPER_ADMIN' => 'super admin'
        ];
    }

    private function trainRoleDecoratorToReturnDecoratedRoles($allRoles, $roleMap)
    {
        $this->roleDecorator->expects($this->exactly(count($allRoles)))
                            ->method('decorate')
                            ->will(
                                $this->returnCallback(
                                    function (RoleInterface $role) use ($roleMap) {
                                        return $roleMap[$role->getRole()];
                                    }
                                )
                            );

        foreach ($allRoles as $index => $role) {
            $this->roleDecorator->expects($this->at($index))
                                ->method('decorate')
                                ->with($role);
        }
    }

    private function getExpectedRoleChoices($reachableRoles)
    {
        $roleMap = $this->getRoleMap();
        $expectedChoices = [];

        // we expect that role choices on the form are only ones that are
        // reachable from the current user's ($user) roles
        $rawExpectedChoices = array_map(
            function (RoleInterface $role) {
                    return $role->getRole();
            },
            $reachableRoles
        );

        foreach ($rawExpectedChoices as $role) {
            $expectedChoices[$role] = $roleMap[$role];
        }

        return $expectedChoices;
    }

    private function getExpectedReadOnlyChoices($allRoles, $reachableRoles)
    {
        $roleMap = $this->getRoleMap();

        $getRoleString = function (RoleInterface $role) {
            return $role->getRole();
        };

        $nonReachableRoles = array_diff(
            array_map($getRoleString, $allRoles),
            array_map($getRoleString, $reachableRoles)
        );

        $expectedReadOnlyChoices = [];
        foreach ($nonReachableRoles as $role) {
            $expectedReadOnlyChoices[$role] = $roleMap[$role];
        }
        return $expectedReadOnlyChoices;
    }
}
