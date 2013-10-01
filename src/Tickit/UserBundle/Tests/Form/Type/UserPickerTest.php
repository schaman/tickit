<?php

namespace Tickit\UserBundle\Tests\Form\Type;

use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\Test\TypeTestCase;
use Tickit\CoreBundle\Tests\AbstractFunctionalTest;
use Tickit\UserBundle\Entity\User;
use Tickit\UserBundle\Form\Type\UserPickerType;

/**
 * User picker form field test
 *
 * @package Tickit\UserBundle\Tests\Form\Type
 * @author  Mark Wilson <mark@89allport.co.uk>
 */
class UserPickerTest extends TypeTestCase
{
    // TODO: remove this when moving to extend AbstractFormTypeTestCase
    protected $formType;

    /**
     * Mocked user manager
     *
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $userManager;

    /**
     * Set up the mock user manager and form type
     *
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();

        $mockUserManager = $this->getMockBuilder('Tickit\\UserBundle\\Manager\\UserManager')
                                ->disableOriginalConstructor()
                                ->getMock();

        $this->userManager = $mockUserManager;

        $this->formType = new UserPickerType($this->userManager);
    }

    public function testNoRestrictionFormFieldOutput()
    {
        $form = $this->factory->create($this->formType);

        $user = new User();
        $user->setForename('Mark')
             ->setSurname('Wilson')
             ->setEmail('mark@89allport.co.uk')
             ->setPlainPassword('password');

//        $this->userManager->expects($this->once())
//             ->method('')

        $this->markTestIncomplete();
    }
}
