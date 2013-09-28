<?php

namespace Tickit\UserBundle\Tests\Form\Type;

use Symfony\Component\Form\Extension\Validator\ValidatorExtension;
use Symfony\Component\Validator\Validation;
use Tickit\CoreBundle\Tests\Form\Type\AbstractFormTypeTestCase;
use Tickit\UserBundle\Entity\User;
use Tickit\UserBundle\Form\Type\UserFormType;

/**
 * UserFormType tests.
 *
 * @package Tickit\UserBundle\Tests\Form\Type
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
     * @return void
     */
    public function testSubmitValidData()
    {
        $form = $this->factory->create($this->formType);

        $user = new User();
        $user->setAdmin(false)
             ->setForename('Forename')
             ->setSurname('Surname')
             ->setEmail('email@domain.com')
             ->setSurname('username')
             ->setPlainPassword('plain password');

        $form->setData($user);

        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($user, $form->getData());

        $view = $form->createView();
        $expectedViewComponents = array('id', 'forename', 'surname', 'username', 'email', 'password', 'admin');
        foreach ($expectedViewComponents as $component) {
            $this->assertArrayHasKey($component, $view->children);
        }
    }

    /**
     * Gets form factory extensions
     */
    protected function configureExtensions()
    {
        $this->enableValidatorExtension();
    }
}
