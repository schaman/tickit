<?php

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
