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
     * @dataProvider getUser
     */
    public function testSubmitValidData(User $user)
    {
        $form = $this->factory->create($this->formType);

        $form->setData($user);

        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($user, $form->getData());

        $expectedViewComponents = array('id', 'forename', 'surname', 'username', 'email', 'password', 'admin');
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

        $expectedViewComponents = array('id', 'forename', 'surname', 'username', 'email', 'password', 'admin');
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
             ->setPlainPassword('plain password');

        return [[$user]];
    }

    /**
     * Gets form factory extensions
     */
    protected function configureExtensions()
    {
        $this->enableValidatorExtension();
    }
}
