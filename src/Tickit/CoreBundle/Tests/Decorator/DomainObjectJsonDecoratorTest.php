<?php

namespace Tickit\CoreBundle\Tests\Decorator;
use Tickit\CoreBundle\Decorator\DomainObjectJsonDecorator;
use Tickit\CoreBundle\Tests\Decorator\Mock\MockDomainObject;

/**
 * DomainObjectJsonDecorator tests
 *
 * @package Tickit\CoreBundle\Tests\Decorator
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class DomainObjectJsonDecoratorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Tests the decorate() method
     *
     * @expectedException \InvalidArgumentException
     *
     * @return void
     */
    public function testDecorateThrowsExceptionForNonObject()
    {
        $decorator = new DomainObjectJsonDecorator();
        $decorator->decorate('', array());
    }

    /**
     * Tests the decorate() method
     *
     * @expectedException \RuntimeException
     *
     * @return void
     */
    public function testDecorateThrowsExceptionForInaccessibleProperty()
    {
        $decorator = new DomainObjectJsonDecorator();
        $decorator->decorate(new MockDomainObject(), array('fake'));
    }

    /**
     * Tests the decorate() method
     *
     * @return void
     */
    public function testDecorateHandlesMockObjectCorrectly()
    {
        $decorator = new DomainObjectJsonDecorator();
        $mock = new MockDomainObject();
        $mock->setName('name');
        $mock->setActive(true);
        $mock->setEnabled(false);

        $decorated = $decorator->decorate($mock, array('name', 'active', 'enabled'));
        $decoratedObject = json_decode($decorated);
        $this->assertInstanceOf('\stdClass', $decoratedObject);
        $this->assertEquals('name', $decoratedObject->name);
        $this->assertTrue($decoratedObject->active);
        $this->assertFalse($decoratedObject->enabled);
    }
}
