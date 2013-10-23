<?php

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
