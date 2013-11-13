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

namespace Tickit\Bundle\CoreBundle\Tests\Form\Type;

use Symfony\Bridge\Doctrine\Form\DoctrineOrmExtension;
use Symfony\Bridge\Doctrine\Test\DoctrineTestHelper;
use Symfony\Component\Form\Extension\Core\CoreExtension;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Validator\ValidatorExtension;
use Symfony\Component\Form\FormBuilder;
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
    private $extensions = array();

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
     */
    protected function enableDoctrineExtension()
    {
        $em = AbstractOrmTest::createTestEntityManager();

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
}
