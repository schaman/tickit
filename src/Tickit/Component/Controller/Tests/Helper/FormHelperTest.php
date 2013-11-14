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

namespace Tickit\Component\Controller\Tests\Helper;

use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\Response;
use Tickit\Component\Controller\Helper\FormHelper;

/**
 * FormHelper tests
 *
 * @package Tickit\Component\Controller\Tests\Helper
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class FormHelperTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $engine;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $formFactory;

    /**
     * Setup
     */
    protected function setUp()
    {
        $this->engine = $this->getMockBuilder('\Symfony\Bundle\FrameworkBundle\Templating\EngineInterface')
                             ->disableOriginalConstructor()
                             ->getMock();

        $this->formFactory = $this->getMockBuilder('\Symfony\Component\Form\FormFactoryInterface')
                                  ->disableOriginalConstructor()
                                  ->getMock();
    }
    
    /**
     * Tests the renderForm() method
     */
    public function testRenderFormCreatesResponse()
    {
        $form = $this->getMockBuilder('\Symfony\Component\Form\FormInterface')
                     ->disableOriginalConstructor()
                     ->getMock();

        $view = new FormView();

        $form->expects($this->once())
             ->method('createView')
             ->will($this->returnValue($view));

        $params = array('form' => $view, 'one' => 1, 'two' => 2);

        $this->engine->expects($this->once())
                     ->method('render')
                     ->with('template.html.twig', $params)
                     ->will($this->returnValue('content'));

        $response = $this->getHelper()->renderForm('template.html.twig', $form, array('one' => 1, 'two' => 2));
        $this->assertInstanceOf('\Symfony\Component\HttpFoundation\Response', $response);
        $this->assertEquals('content', $response->getContent());
    }

    /**
     * Tests the createForm() method
     */
    public function testCreateFormCreatesForm()
    {
        $data = array('property' => 'value');
        $options = array('option' => 'value');

        $this->formFactory->expects($this->once())
                          ->method('create')
                          ->with('form-type', $data, $options)
                          ->will($this->returnValue('form-value'));

        $form = $this->getHelper()->createForm('form-type', $data, $options);
        $this->assertEquals('form-value', $form);
    }

    /**
     * Gets new FormHelper instance
     *
     * @return FormHelper
     */
    private function getHelper()
    {
        return new FormHelper($this->engine, $this->formFactory);
    }
}
