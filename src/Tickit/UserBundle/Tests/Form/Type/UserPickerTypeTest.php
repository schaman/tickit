<?php

namespace Tickit\UserBundle\Tests\Form\Type;

use Doctrine\Common\Collections\ArrayCollection;
use Tickit\CoreBundle\Tests\Form\Type\AbstractFormTypeTestCase;
use Tickit\UserBundle\Entity\User;
use Tickit\UserBundle\Form\Type\Picker\UserPickerType;

/**
 * User picker form field test
 *
 * @package Tickit\UserBundle\Tests\Form\Type
 * @author  Mark Wilson <mark@89allport.co.uk>
 */
class UserPickerTypeTest extends AbstractFormTypeTestCase
{
    /**
     * Tests form data of an invalid user based on the restrictions returns the correct display names - none
     */
    public function testInvalidUserSubmissionWithFormRestriction()
    {
        $this->markTestIncomplete('Not yet implemented restrictions');
    }
}
