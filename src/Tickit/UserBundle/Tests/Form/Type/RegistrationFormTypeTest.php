<?php

namespace Tickit\UserBundle\Tests\Form\Type;

use Symfony\Component\Form\Extension\Validator\ValidatorExtension;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\Validator\Validation;
use Tickit\UserBundle\Entity\User;
use Tickit\UserBundle\Form\Type\RegistrationFormType;

/**
 * RegistrationFormType tests,
 *
 * @package Tickit\UserBundle\Tests\Form\Type
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class RegistrationFormTypeTest extends TypeTestCase
{
    /**
     * The form under test.
     *
     * @var RegistrationFormType
     */
    private $formType;

    /**
     * Setup
     */
    protected function setUp()
    {
        parent::setUp();

        $this->formType = new RegistrationFormType('Tickit\UserBundle\Entity\User');
    }

    /**
     * Tests the form submit
     *
     * @return void
     */
    public function testSubmitValidData()
    {
        $form = $this->factory->create($this->formType);

        $user = new User();
        $user->setForename('Forename')
             ->setSurname('Surname')
             ->setEmail('email@domain.com')
             ->setSurname('username')
             ->setPlainPassword('plain password');

        $form->setData($user);

        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($user, $form->getData());

        $view = $form->createView();
        $expectedViewComponents = array('forename', 'surname', 'username', 'email', 'password');
        foreach ($expectedViewComponents as $component) {
            $this->assertArrayHasKey($component, $view->children);
        }
    }

    /**
     * @return array
     */
    protected function getExtensions()
    {
        return array(new ValidatorExtension(Validation::createValidator()));
    }
}
