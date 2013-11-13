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

use Tickit\Component\Model\Client\Client;
use Tickit\Bundle\ClientBundle\Form\Type\ClientFormType;
use Tickit\Bundle\CoreBundle\Tests\Form\Type\AbstractFormTypeTestCase;

/**
 * ClientFormType tests
 *
 * @package Tickit\Bundle\ClientBundle\Tests\Form\Type
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class ClientFormTypeTest extends AbstractFormTypeTestCase
{
    /**
     * The form type under test
     *
     * @var ClientFormType
     */
    private $type;

    /**
     * Setup
     */
    protected function setUp()
    {
        $this->formType = new ClientFormType();

        parent::setUp();
    }
    
    /**
     * Tests the form submit
     */
    public function testSubmitValidData()
    {
        $form = $this->factory->create($this->formType);

        $client = new Client();
        $client->setName('client name')
               ->setNotes('client notes')
               ->setUrl('http://client.com/home-page');

        $form->setData($client);

        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($client, $form->getData());

        $expectedViewComponents = ['name', 'url', 'notes'];
        $this->assertViewHasComponents($expectedViewComponents, $form->createView());
    }
}
