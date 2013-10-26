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

namespace Tickit\CoreBundle\Tests\Controller\Helper;

use Symfony\Component\Security\Csrf\CsrfToken;
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
    private $tokenManager;

    /**
     * Setup
     */
    protected function setUp()
    {
        $this->tokenManager = $this->getMockForAbstractClass(
            '\Symfony\Component\Security\Csrf\CsrfTokenManagerInterface'
        );
    }
    
    /**
     * Tests the checkCsrfToken() method
     *
     * @expectedException \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function testCheckCsrfTokenThrowsExceptionForInvalidToken()
    {
        $expectedToken = new CsrfToken('intent', 'token');

        $this->tokenManager->expects($this->once())
                           ->method('isTokenValid')
                           ->with($expectedToken)
                           ->will($this->returnValue(false));

        $this->getHelper()->checkCsrfToken('token', 'intent');
    }

    /**
     * Tests the checkCsrfToken() method
     */
    public function testCheckCsrfTokenDoesNotThrowExceptionForValidToken()
    {
        $expectedToken = new CsrfToken('intent', 'token');

        $this->tokenManager->expects($this->once())
                             ->method('isTokenValid')
                             ->with($expectedToken)
                             ->will($this->returnValue(true));

        $this->getHelper()->checkCsrfToken('token', 'intent');
    }

    /**
     * Tests the generateCsrfToken() method
     */
    public function testGenerateCsrfTokenReturnsToken()
    {
        $token = new CsrfToken('intent', 'token');
        $this->tokenManager->expects($this->once())
                           ->method('getToken')
                           ->with('intent')
                           ->will($this->returnValue($token));

        $this->assertEquals($token, $this->getHelper()->generateCsrfToken('intent'));
    }

    /**
     * Gets a new helper instance
     *
     * @return CsrfHelper
     */
    private function getHelper()
    {
        return new CsrfHelper($this->tokenManager);
    }
}
