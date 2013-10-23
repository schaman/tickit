<?php

/*
 * 
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
 * 
 */

namespace Tickit\CoreBundle\Tests\Controller\Helper;

use Tickit\CoreBundle\Controller\Helper\CsrfHelper;

/**
 * CsrfHelper tests
 *
 * @package Tickit\CoreBundle\Tests\Controller\Helper
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class CsrfHelperTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $tokenGenerator;

    /**
     * Setup
     */
    protected function setUp()
    {
        $this->tokenGenerator = $this->getMockBuilder('\Symfony\Component\Security\Csrf\CsrfTokenGeneratorInterface')
                                     ->disableOriginalConstructor()
                                     ->getMock();
    }
    
    /**
     * Tests the checkCsrfToken() method
     *
     * @expectedException \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function testCheckCsrfTokenThrowsExceptionForInvalidToken()
    {
        $this->tokenGenerator->expects($this->once())
                             ->method('isCsrfTokenValid')
                             ->with('intent', 'token')
                             ->will($this->returnValue(false));

        $this->getHelper()->checkCsrfToken('token', 'intent');
    }

    /**
     * Tests the checkCsrfToken() method
     */
    public function testCheckCsrfTokenDoesNotThrowExceptionForValidToken()
    {
        $this->tokenGenerator->expects($this->once())
                             ->method('isCsrfTokenValid')
                             ->with('intent', 'token')
                             ->will($this->returnValue(true));

        $this->getHelper()->checkCsrfToken('token', 'intent');
    }

    /**
     * Tests the generateCsrfToken() method
     */
    public function testGenerateCsrfTokenReturnsToken()
    {
        $this->tokenGenerator->expects($this->once())
                             ->method('generateCsrfToken')
                             ->with('intent')
                             ->will($this->returnValue('token'));

        $token = $this->getHelper()->generateCsrfToken('intent');
        $this->assertEquals('token', $token);
    }

    /**
     * Gets a new helper instance
     *
     * @return CsrfHelper
     */
    private function getHelper()
    {
        return new CsrfHelper($this->tokenGenerator);
    }
}
