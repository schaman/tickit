<?php

namespace Tickit\CoreBundle\Tests\Controller\Helper;

use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\Response;
use Tickit\CoreBundle\Controller\Helper\BaseHelper;
use Tickit\UserBundle\Entity\User;

/**
 * BaseHelper tests
 *
 * @package Tickit\CoreBundle\Tests\Controller\Helper
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class BaseHelperTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $request;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $securityContext;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $objectDecorator;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $router;

    /**
     * Setup
     */
    protected function setUp()
    {
        $this->request = $this->getMockBuilder('\Symfony\Component\HttpFoundation\Request')
                              ->disableOriginalConstructor()
                              ->getMock();

        $this->securityContext = $this->getMockBuilder('\Symfony\Component\Security\Core\SecurityContextInterface')
                                      ->disableOriginalConstructor()
                                      ->getMock();

        $this->objectDecorator = $this->getMockBuilder('\Tickit\CoreBundle\Decorator\DomainObjectDecoratorInterface')
                                      ->disableOriginalConstructor()
                                      ->getMock();

        $this->router = $this->getMockBuilder('\Symfony\Component\Routing\RouterInterface')
                             ->disableOriginalConstructor()
                             ->getMock();
    }
    
    /**
     * Tests the getObjectDecorator() method
     */
    public function testGetObjectDecoratorReturnsCorrectInstance()
    {
        $this->assertSame($this->objectDecorator, $this->getHelper()->getObjectDecorator());
    }

    /**
     * Tests the getRequest() method
     */
    public function testGetRequestReturnsCorrectInstance()
    {
        $this->assertSame($this->request, $this->getHelper()->getRequest());
    }

    /**
     * Tests the getRouter() method
     */
    public function testGetRouterReturnsCorrectInstance()
    {
        $this->assertSame($this->router, $this->getHelper()->getRouter());
    }

    /**
     * Tests the generateUrl() method
     */
    public function testGenerateUrlGeneratesRoute()
    {
        $params = array('paremeter' => 'value');

        $this->router->expects($this->once())
                     ->method('generate')
                     ->with('route-name', $params)
                     ->will($this->returnValue('route'));

        $route = $this->getHelper()->generateUrl('route-name', $params);
        $this->assertEquals('route', $route);
    }

    /**
     * Tests the getUser() method
     */
    public function testGetUserReturnsNullForInvalidToken()
    {
        $this->securityContext->expects($this->once())
                              ->method('getToken')
                              ->will($this->returnValue(null));

        $this->assertNull($this->getHelper()->getUser());
    }

    /**
     * Tests the getUser() method
     */
    public function testGetUserReturnsNullForInvalidUser()
    {
        $token = $this->getMockBuilder('\Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken')
                      ->disableOriginalConstructor()
                      ->getMock();

        $token->expects($this->once())
              ->method('getUser')
              ->will($this->returnValue(null));

        $this->securityContext->expects($this->once())
                              ->method('getToken')
                              ->will($this->returnValue($token));

        $this->assertNull($this->getHelper()->getUser());
    }

    /**
     * Tests the getUser() method
     */
    public function testGetUserReturnsUser()
    {
        $token = $this->getMockBuilder('\Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken')
                              ->disableOriginalConstructor()
                              ->getMock();

        $user = new User();

        $token->expects($this->once())
              ->method('getUser')
              ->will($this->returnValue($user));

        $this->securityContext->expects($this->once())
                              ->method('getToken')
                              ->will($this->returnValue($token));

        $this->assertSame($user, $this->getHelper()->getUser());
    }

    /**
     * Tests the createForm() method
     */
    public function testCreateFormCreatesForm()
    {

    }

    /**
     * Gets new BaseHelper instance
     *
     * @return BaseHelper
     */
    private function getHelper()
    {
        return new BaseHelper($this->request, $this->securityContext, $this->objectDecorator, $this->router);
    }
}
