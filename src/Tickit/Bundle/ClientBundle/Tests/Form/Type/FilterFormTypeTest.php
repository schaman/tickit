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

namespace Tickit\Bundle\ClientBundle\Tests\Form\Type;

use Tickit\Bundle\ClientBundle\Form\Type\FilterFormType;
use Tickit\Bundle\CoreBundle\Tests\Form\Type\AbstractFormTypeTestCase;
use Tickit\Component\Model\Client\Client;

/**
 * FilterFormType tests
 *
 * @package Tickit\Bundle\ClientBundle\Tests\Form\Type
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class FilterFormTypeTest extends AbstractFormTypeTestCase
{
    /**
     * Setup
     */
    protected function setUp()
    {
        parent::setUp();

        $this->formType = new FilterFormType();
    }
    
    /**
     * Tests the form submit
     */
    public function testSubmitValidData()
    {
        $form = $this->factory->create($this->formType);

        $data = [
            'status' => Client::STATUS_ARCHIVED,
            'name' => 'client name'
        ];

        $form->submit($data);

        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($data, $form->getData());

        $expectedFields = ['status', 'name'];
        $this->assertViewHasComponents($expectedFields, $form->createView());
    }
}
 