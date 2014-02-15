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

namespace Tickit\Bundle\UserBundle\Tests\Form\Type;

use Tickit\Bundle\CoreBundle\Tests\Form\Type\AbstractFormTypeTestCase;
use Tickit\Bundle\UserBundle\Form\Type\FilterFormType;

/**
 * FilterFormType tests
 *
 * @package Tickit\Bundle\UserBundle\Tests\Form\Type
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class FilterFormTypeTest extends AbstractFormTypeTestCase
{
    /**
     * Setup
     */
    protected function setUp()
    {
        $this->formType = new FilterFormType();

        parent::setUp();
    }
    
    /**
     * Tests the form submit
     */
    public function testSubmitValidData()
    {
        $form = $this->factory->create($this->formType);

        $data = [
            'forename' => 'james',
            'surname' => 'dean',
            'username' => 'jdean',
            'email' => 'jdean@googlemail.com',
            'isAdmin' => '1',
            'lastActive' => '2010-01-01 08:00:00'
        ];

        $form->submit($data);

        $expectedData = [
            'forename' => 'james',
            'surname' => 'dean',
            'username' => 'jdean',
            'email' => 'jdean@googlemail.com',
            'isAdmin' => '1',
            'lastActive' => new \DateTime('2010-01-01 08:00:00')
        ];

        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($expectedData, $form->getData());

        $expectedFields = ['forename', 'surname', 'username', 'email', 'isAdmin', 'lastActive'];
        $this->assertViewHasComponents($expectedFields, $form->createView());
    }
}
