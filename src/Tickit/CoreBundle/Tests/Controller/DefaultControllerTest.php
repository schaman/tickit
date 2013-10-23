<?php

/*
 * Tickit, an source web based bug management tool.
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

namespace Tickit\CoreBundle\Tests\Controller;

use Symfony\Component\HttpFoundation\Response;
use Tickit\CoreBundle\Controller\DefaultController;
use Tickit\CoreBundle\Tests\AbstractUnitTest;

/**
 * DefaultController tests
 *
 * @package Tickit\CoreBundle\Tests\Controller
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class DefaultControllerTest extends AbstractUnitTest
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $templateEngine;

    /**
     * Setup
     */
    protected function setUp()
    {
        $this->templateEngine = $this->getMockTemplateEngine();
    }
    
    /**
     * Tests the defaultAction() method
     */
    public function testDefaultActionRendersCorrectTemplate()
    {
        $expectedResponse = new Response();

        $this->templateEngine->expects($this->once())
                             ->method('renderResponse')
                             ->with('::base.html.twig')
                             ->will($this->returnValue($expectedResponse));

        $response = $this->getController()->defaultAction();
        $this->assertSame($expectedResponse, $response);
    }

    /**
     * Gets a new controller
     *
     * @return DefaultController
     */
    private function getController()
    {
        return new DefaultController($this->templateEngine);
    }
}
