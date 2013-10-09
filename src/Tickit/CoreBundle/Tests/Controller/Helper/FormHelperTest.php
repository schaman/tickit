<?php

namespace Tickit\CoreBundle\Tests\Controller\Helper;

use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\Response;
use Tickit\CoreBundle\Controller\Helper\FormHelper;

/**
 * FormHelper tests
 *
 * @package Tickit\CoreBundle\Tests\Controller\Helper
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

        $content = $this->getHelper()->renderForm('template.html.twig', $form, array('one' => 1, 'two' => 2));
        $this->assertEquals('content', $content);
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
