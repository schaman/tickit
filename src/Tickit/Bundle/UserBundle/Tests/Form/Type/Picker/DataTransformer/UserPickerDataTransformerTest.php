<?php

namespace Tickit\Bundle\UserBundle\Tests\Form\Type\Picker\DataTransformer;

use Tickit\Bundle\CoreBundle\Tests\AbstractUnitTest;
use Tickit\Component\Model\User\User;
use Tickit\Bundle\UserBundle\Form\Type\Picker\DataTransformer\UserPickerDataTransformer;

/**
 * UserPickerDataTransformer tests
 *
 * @package Tickit\Bundle\UserBundle\Tests\Form\Type\Picker\DataTransformer
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class UserPickerDataTransformerTest extends AbstractUnitTest
{
    /**
     * @var UserPickerDataTransformer
     */
    private $sut;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $manager;

    /**
     * Setup
     */
    protected function setUp()
    {
        $this->manager = $this->getMockBuilder('\Tickit\Component\Entity\Manager\UserManager')
                              ->disableOriginalConstructor()
                              ->getMock();

        $this->sut = new UserPickerDataTransformer($this->manager);
    }
    
    /**
     * Tests the getEntityIdentifier() method
     */
    public function testGetEntityIdentifierReturnsCorrectProperty()
    {
        $method = static::getNonAccessibleMethod(get_class($this->sut), 'getEntityIdentifier');

        $this->assertEquals('id', $method->invoke($this->sut));
    }

    /**
     * Tests the findEntityByIdentifier() method
     */
    public function testFindEntityByIdentifierFindsEntity()
    {
        $method = static::getNonAccessibleMethod(get_class($this->sut), 'findEntityByIdentifier');

        $user = new User();
        $user->setId(1);

        $this->manager->expects($this->once())
                      ->method('find')
                      ->with(1)
                      ->will($this->returnValue($user));

        $this->assertEquals($user, $method->invokeArgs($this->sut, [1]));
    }
}
