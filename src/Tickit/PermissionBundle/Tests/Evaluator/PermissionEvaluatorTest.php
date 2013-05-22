<?php

namespace Tickit\PermissionBundle\Tests\Evaluator;

use Tickit\CoreBundle\Tests\AbstractFunctionalTest;
use Tickit\PermissionBundle\Entity\GroupPermissionValue;
use Tickit\UserBundle\Entity\Group;

/**
 * PermissionEvaluator tests.
 *
 * @package Tickit\PermissionBundle\Tests\Evaluator
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class PermissionEvaluatorTest extends AbstractFunctionalTest
{
    /**
     * Tests the service container configuration.
     *
     * @return void
     */
    public function testServiceContainerReturnsCorrectInstance()
    {
        $container = static::createClient()->getContainer();
        $evaluator = $container->get('tickit_permission.evaluator');

        $this->assertInstanceOf('Tickit\PermissionBundle\Evaluator\PermissionEvaluator', $evaluator);
    }

    /**
     * Tests the has() method
     *
     * @expectedException \RuntimeException
     *
     * @return void
     */
    public function testHasThrowsExceptionWhenNoPermissionsHaveBeenLoaded()
    {
        $container = static::createClient()->getContainer();
        $evaluator = $container->get('tickit_permission.evaluator');

        $evaluator->hasPermission('tickets.attachments.create');
    }

    /**
     * Tests the has() method
     *
     * @return void
     */
    public function testHasReturnsTrueForGroupGrantedPermission()
    {
        $container = $this->getAuthenticatedClient(static::$admin)->getContainer();
        $container->get('tickit_permission.loader')->loadForUser(static::$admin);
        $evaluator = $container->get('tickit_permission.evaluator');

        $this->assertTrue($evaluator->hasPermission('tickets.attachments.create'));
    }

    /**
     * Tests the has() method
     *
     * @return void
     */
    public function testHasReturnsFalseForGroupProhibitedPermission()
    {
        $faker = $this->getFakerGenerator();
        $group = new Group('Group-' . $faker->sha1);

        $doctrine = static::createClient()->getContainer()->get('doctrine');
        $em = $doctrine->getManager();

        $em->persist($group);
        $em->flush();

        $allPermissions = $doctrine->getRepository('TickitPermissionBundle:Permission')->findAll();

        foreach ($allPermissions as $permission) {
            $groupValue = new GroupPermissionValue();
            $groupValue->setGroup($group);
            $groupValue->setPermission($permission);
            $groupValue->setValue(false);
        }

        $user = $this->createNewUser(false)
                     ->setGroup($group);

        $em->persist($user);
        $em->flush();

        $container = $this->getAuthenticatedClient($user)->getContainer();
        $loader = $container->get('tickit_permission.loader');
        $loader->loadForUser($user);
        $evaluator = $container->get('tickit_permission.evaluator');

        $this->assertFalse($evaluator->hasPermission('tickets.attachments.create'));
    }
}
