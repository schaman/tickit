<?php

namespace Tickit\PermissionBundle\Tests\Form\DataTransformer;

use Tickit\CoreBundle\Tests\AbstractFunctionalTest;
use Tickit\PermissionBundle\Entity\Permission;
use Tickit\PermissionBundle\Form\DataTransformer\PermissionToPermissionNameTransformer;

/**
 * PermissionToPermissionNameTransformer tests.
 *
 * @package Tickit\PermissionBundle\Tests\Form\DataTransformer
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class PermissionToPermissionNameTransformerTest extends AbstractFunctionalTest
{
    /**
     * Tests the transform() method
     *
     * @return void
     */
    public function testTransformReturnsCorrectValue()
    {
        $transformer = $this->getTransformer();
        $permission = new Permission();
        $permission->setName('test name');

        $name = $transformer->transform($permission);
        $this->assertEquals('test name', $name);
    }

    /**
     * Tests the transform() method
     *
     * @expectedException \Symfony\Component\Form\Exception\UnexpectedTypeException
     *
     * @return void
     */
    public function testTransformThrowsExceptionForInvalidType()
    {
        $transformer = $this->getTransformer();
        $transformer->transform('not a Permission');
    }

    /**
     * Tests the reverseTransform() method
     *
     * @return void
     */
    public function testReverseTransformReturnsCorrectValue()
    {
        $container = static::createClient()->getContainer();
        $permission = $container->get('doctrine')
                                ->getRepository('TickitPermissionBundle:Permission')
                                ->findOneBySystemName('users.view');

        $transformer = $this->getTransformer();
        $transformedPermission = $transformer->reverseTransform($permission->getName());

        $this->assertInstanceOf('Tickit\PermissionBundle\Entity\Permission', $transformedPermission);
        $this->assertEquals($transformedPermission->getName(), $permission->getName());
    }

    /**
     * Tests the reverseTransform() method
     *
     * @expectedException \Symfony\Component\Form\Exception\UnexpectedTypeException
     *
     * @return void
     */
    public function testReverseTransformThrowsExceptionForInvalidType()
    {
        $transformer = $this->getTransformer();
        $transformer->reverseTransform(10000);
    }

    /**
     * Tests the reverseTransform() method
     *
     * @expectedException \Symfony\Component\Form\Exception\TransformationFailedException
     *
     * @return void
     */
    public function testReverseTransformThrowsExceptionForNonExistentPermission()
    {
        $transformer = $this->getTransformer();
        $transformer->reverseTransform('not a valid permission name');
    }

    /**
     * Gets a transformer instance
     *
     * @return PermissionToPermissionNameTransformer
     */
    protected function getTransformer()
    {
        $container = static::createClient()->getContainer();
        $em = $container->get('doctrine')->getManager();
        $transformer = new PermissionToPermissionNameTransformer($em);

        return $transformer;
    }
}
