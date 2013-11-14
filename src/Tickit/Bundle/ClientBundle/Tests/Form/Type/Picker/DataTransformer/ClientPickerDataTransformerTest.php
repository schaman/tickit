<?php

namespace Tickit\Bundle\ClientBundle\Tests\Form\Type\Picker\DataTransformer;

use Tickit\Component\Model\Client\Client;
use Tickit\Bundle\ClientBundle\Form\Type\Picker\DataTransformer\ClientPickerDataTransformer;
use Tickit\Component\Test\AbstractUnitTest;

/**
 * ClientPickerDataTransformer tests
 *
 * @package Tickit\Bundle\ClientBundle\Tests\Form\Type\Picker\DataTransformer
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class ClientPickerDataTransformerTest extends AbstractUnitTest
{
    /**
     * @var ClientPickerDataTransformer
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
        $this->manager = $this->getMockBuilder('\Tickit\Component\Entity\Manager\ClientManager')
                              ->disableOriginalConstructor()
                              ->getMock();

        $this->sut = new ClientPickerDataTransformer($this->manager);
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

        $client = new Client();
        $client->setId(1);

        $this->manager->expects($this->once())
                      ->method('find')
                      ->with(1)
                      ->will($this->returnValue($client));

        $this->assertEquals($client, $method->invokeArgs($this->sut, [1]));
    }
}
