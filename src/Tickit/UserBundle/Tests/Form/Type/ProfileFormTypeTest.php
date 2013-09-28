<?php

namespace Tickit\UserBundle\Tests\Form\Type;

use Symfony\Component\Form\Extension\Validator\ValidatorExtension;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Validator\Validation;
use Tickit\CoreBundle\Tests\Form\Type\AbstractFormTypeTestCase;
use Tickit\UserBundle\Entity\User;
use Tickit\UserBundle\Form\Type\ProfileFormType;

/**
 * ProfileFormType tests
 *
 * @package Tickit\UserBundle\Tests\Form\Type
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class ProfileFormTypeTest extends AbstractFormTypeTestCase
{
    /**
     * Setup
     */
    protected function setUp()
    {
        parent::setUp();

        $this->formType = new ProfileFormType('Tickit\UserBundle\Entity\User');
    }

    /**
     * Tests the form submit
     *
     * @return void
     */
    public function testSubmitValidData()
    {
        $user = new User();
        $user->setForename('forename')
             ->setSurname('surname')
             ->setEmail('email@domain.com')
             ->setPlainPassword('password')
             ->setPassword('hashed-password');

        $form = $this->factory->create($this->formType);
        $form->setData($user);

        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($user, $form->getData());

        $expectedViewComponents = array('forename', 'surname', 'password');
        $view = $form->createView();
        foreach ($expectedViewComponents as $component) {
            $this->assertArrayHasKey($component, $view->children);
        }
    }

    /**
     * {@inheritDoc}
     */
    protected function configureExtensions()
    {
        $this->enableValidatorExtension();
    }
}