<?php

namespace Tickit\CoreBundle\Tests\Form\Type;

use Symfony\Bridge\Doctrine\Form\DoctrineOrmExtension;
use Symfony\Bridge\Doctrine\Test\DoctrineTestHelper;
use Symfony\Component\Form\Extension\Core\CoreExtension;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Validator\ValidatorExtension;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\Validator\Validation;

/**
 * Abstract test case for FormType classes.
 *
 * @package Tickit\CoreBundle\Tests\Form\Type
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
        $em = DoctrineTestHelper::createTestEntityManager();

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
}
