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

namespace Tickit\Bundle\CoreBundle\Tests\Form\Type;

use Doctrine\ORM\EntityManager;
use Faker\Factory;
use Faker\Generator;
use Symfony\Bridge\Doctrine\Form\DoctrineOrmExtension;
use Symfony\Component\Form\Extension\Core\CoreExtension;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Validator\ValidatorExtension;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\Validator\Validation;
use Tickit\Bundle\CoreBundle\Tests\AbstractOrmTest;

/**
 * Abstract test case for FormType classes.
 *
 * @package Tickit\Bundle\CoreBundle\Tests\Form\Type
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class AbstractFormTypeTestCase extends TypeTestCase
{
    /**
     * Form extensions
     *
     * @var array
     */
    protected $extensions = array();

    /**
     * The form under test
     *
     * @var FormType
     */
    protected $formType;

    /**
     * Setup
     */
    protected function setUp()
    {
        $this->configureExtensions();

        parent::setUp();
    }

    /**
     * Gets the form extensions for the test case
     *
     * @return array
     */
    protected function getExtensions()
    {
        return $this->extensions;
    }

    /**
     * Configures form extensions for the test case
     */
    protected function configureExtensions()
    {

    }

    /**
     * Enables the doctrine form extension
     *
     * @param EntityManager $em An entity manager to use in the extension (optional)
     */
    protected function enableDoctrineExtension(EntityManager $em = null)
    {
        if (null === $em) {
            $em = AbstractOrmTest::createTestEntityManager();
        }

        $manager = $this->getMock('Doctrine\Common\Persistence\ManagerRegistry');

        $manager->expects($this->any())
                ->method('getManager')
                ->will($this->returnValue($em));

        $manager->expects($this->any())
                ->method('getManagerForClass')
                ->will($this->returnValue($em));

        $this->extensions[] = new DoctrineOrmExtension($manager);
    }

    /**
     * Enables the entity type form extension
     *
     * @param array $entities An array of entities to support in the extension indexed by their fully qualified
     *                        class name
     */
    protected function enableEntityTypeExtension(array $entities)
    {
        $em = $this->getMockBuilder('\Doctrine\ORM\EntityManager')
                   ->disableOriginalConstructor()
                   ->getMock();

        $metaData = $this->getMockBuilder('\Doctrine\Common\Persistence\Mapping\ClassMetadata')
                         ->disableOriginalConstructor()
                         ->getMock();

        $entityClasses = array_keys($entities);
        $nameMatcher = call_user_func_array([$this, 'onConsecutiveCalls'], $entityClasses);

        $metaData->expects($this->exactly(2))
                 ->method('getName')
                 ->will($nameMatcher);

        $metaData->expects($this->any())
                 ->method('getIdentifierFieldNames')
                 ->will($this->returnValue('id'));

        $em->expects($this->any())
           ->method('getClassMetaData')
           ->will($this->returnValue($metaData));

        $repositories = [];

        foreach ($entityClasses as $className) {
            $repo = $this->getMockBuilder('\Doctrine\ORM\EntityRepository')
                         ->disableOriginalConstructor()
                         ->getMock();

            $repo->expects($this->any())
                 ->method('findAll')
                 ->will($this->returnValue($entities[$className]));

            $repositories[] = $repo;
        }

        $repositoryMatcher = call_user_func_array([$this, 'onConsecutiveCalls'], $repositories);

        $em->expects($this->exactly(count($repositories)))
           ->method('getRepository')
           ->will($repositoryMatcher);

        $em->expects($this->any())
           ->method('contains')
           ->will($this->returnValue(true));

        $em->expects($this->any())
           ->method('contains');


        $totalEntities = 0;
        $identifierMappings = [];
        array_walk($entities, function ($e) use (&$totalEntities, &$identifierMappings) {
            $totalEntities += count($e);
            foreach ($e as $entity) {
                $identifierMappings[] = ['id' => $entity->getId()];
            }
        });

        $identifierValueMatcher = call_user_func_array([$this, 'onConsecutiveCalls'], $identifierMappings);
        $metaData->expects($this->exactly($totalEntities))
                 ->method('getIdentifierValues')
                 ->will($identifierValueMatcher);

        $this->enableDoctrineExtension($em);
    }

    /**
     * Enables the core form extension
     */
    protected function enableCoreExtension()
    {
        $this->extensions[] = new CoreExtension();
    }

    /**
     * Enables the validator form extension
     */
    protected function enableValidatorExtension()
    {
        $this->extensions[] = new ValidatorExtension(Validation::createValidator());
    }

    /**
     * Assert that a FormView instance contains all given component names
     *
     * @param array    $componentNames The component names
     * @param FormView $view           The FormView instance
     *
     * @return void
     */
    protected function assertViewHasComponents(array $componentNames, FormView $view)
    {
        foreach ($componentNames as $name) {
            $this->assertArrayHasKey($name, $view->children);
        }
    }

    /**
     * Gets a mock entity decorator instance
     *
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getMockEntityDecorator()
    {
        return $this->getMock('\Tickit\Component\Decorator\Entity\EntityDecoratorInterface');
    }

    /**
     * Gets a mock instance of a data transformer for pickers
     *
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getMockPickerDataTransformer()
    {
        return $this->getMockForAbstractClass(
            'Tickit\Bundle\PickerBundle\Form\Type\Picker\DataTransformer\AbstractPickerDataTransformer',
            [],
            '',
            false,
            false,
            true,
            ['transform', 'reverseTransform']
        );
    }

    /**
     * Gets a faker generator instance
     *
     * @return Generator
     */
    protected function getFakerGenerator()
    {
        return Factory::create();
    }

    /**
     * Gets a mock data transformer
     *
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getMockDataTransformer()
    {
        return $this->getMock('\Symfony\Component\Form\DataTransformerInterface');
    }
}
